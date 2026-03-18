<template>
    <div class="mx-auto max-w-5xl p-6">
        <div class="mb-4 flex gap-4">
            <a href="/rating" class="text-blue-600 hover:underline">Рейтинг</a>
        </div>

        <h1 class="text-2xl font-bold mb-6">Выберите лучший автомобиль</h1>

        <div v-if="error" class="mb-4 rounded bg-red-100 p-3 text-red-700">
            {{ error }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                v-for="car in pair"
                :key="car.id"
                class="rounded border p-4 shadow-sm"
            >
                <div class="aspect-video mb-3 bg-gray-100 flex items-center justify-center overflow-hidden">
                    <img
                        v-if="car.images"
                        :src="car.getImageUrl()"
                        alt=""
                        class="h-full w-full object-cover"
                        v-ezplus-tint="{
                            tintColour: '#3b82f6',
                            tintOpacity: 0.35,
                            zoomType: 'lens',
                            lensSize: 220
                        }"
                    />
                    <div v-else class="text-gray-400">Нет изображения</div>
                </div>
                <div class="mb-3">
                    <div class="text-lg font-semibold">{{ car.brand?.name }} {{ car.model }}</div>
                    <div class="text-gray-600">Год: {{ car.year }}</div>
                </div>
                <button
                    class="w-full rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-60"
                    :disabled="loading"
                    @click="vote(car.id)"
                >
                    Голосовать
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from "pinia";
import {Car, useCarsStore} from "../Stores/cars.js";
import {ref} from "vue";
import {ezplusTint} from "../Directives/ezplusTint.js";

export default {
    directives: {ezplusTint},
    data() {
        return {
            pair: ref([]),
            error: null,
            loading: false,
        }
    },
    mounted() {
        this.refreshPair()
    },
    methods: {
        ...mapActions(useCarsStore, ["getRandomCars", "voteTheCar"]),
        async vote(carId) {
            this.loading = true;

            await this.voteTheCar(carId)
            await this.refreshPair()
        },
        async refreshPair() {
            this.loading = true;

            this.pair = await this.getRandomCars(2)

            this.loading = false;
        }
    }
}
</script>

<style scoped>
</style>
