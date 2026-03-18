import {defineStore} from "pinia"
import {ApiClient} from "../Services/ApiClient/ApiClient";

export interface ICar {
    id: number
    brand: ICarBrand
    images: ICarImage[]
    year: number
    model: string
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

    constructor(data: ICar) {
        this.id = data.id
        this.brand = data.brand
        this.images = data.images
        this.year = data.year
        this.model = data.model
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
