import {defineStore} from "pinia"
import {ApiClient} from "../Services/ApiClient/ApiClient";

export interface ICar {
    id: number
    brand: ICarBrand
    images: ICarImage[]
    year: number
    model: string
    votes_count: number
    brand_votes_count: number
}

export interface ICarBrand {
    id: number
    name: string
}

export interface ICarImage {
    id: number
    disk: string
    path: string
}

export class Car {
    id: number
    brand: ICarBrand
    images: ICarImage[]
    year: number
    model: string
    votesCount: number
    brandVotesCount: number

    constructor(data: ICar) {
        this.id = data.id
        this.brand = data.brand
        this.images = data.images
        this.year = data.year
        this.model = data.model
        this.votesCount = data.votes_count
        this.brandVotesCount = data.brand_votes_count
    }

    getImageUrl(): string {
        return this.images[0].path ?? null
    }
}

interface State {}


export const useCarsStore = defineStore("cars", {
    state: (): State => ({}),

    actions: {
        async getRandomCars(count: number): Promise<Car[]> {
            let data = await ApiClient.post(
                {
                    route: '/api/v1/cars/random',
                    data: {
                        "available_fields": [
                            "id", "images", "model", "brand", "year"
                        ],
                        "limit": count
                    }
                }
            );

            return await data.data.map((car: ICar) => new Car(car));
        },

        async getCarsList(
            {
                yearFrom = null,
                yearTo = null,
                model = null,
                limit = 20,
                offset = 0,
                orderBy = 'votes_count',
                orderDirection = 'desc'
            }: {
                yearFrom?: number;
                yearTo?: number;
                model?: string;
                limit?: number;
                offset?: number;
                orderBy?: string;
                orderDirection?: string;
            }
        ): Promise<Car[]> {
            let filter = {
                year: null,
                model: model,
            };

            let sort = {}

            if (yearFrom && yearTo) {
                filter.year = {
                    gt: yearFrom,
                    lt: yearTo
                }
            } else if (yearFrom) {
                filter.year = {
                    gt: yearFrom,
                }
            } else if (yearTo) {
                filter.year = {
                    lt: yearTo,
                }
            }

            if (orderBy) {
                sort = {
                    [orderBy]: orderDirection,
                }
            }

            let data = await ApiClient.post(
                {
                    route: '/api/v1/cars/list',
                    data: {
                        available_fields: [
                            'id', 'images', 'model', 'brand', 'year', 'votes_count', 'brand_votes_count'
                        ],
                        filter: filter,
                        limit: limit,
                        offset: offset,
                        sort: sort
                    },
                }
            );

            return await data.data.map((car: ICar) => new Car(car))
        },

        async voteTheCar(carId: number): Promise<void> {
            await ApiClient.post({
                route: '/api/v1/cars/vote',
                data: {
                    car_id: carId
                }
            });
        }
    },
})
