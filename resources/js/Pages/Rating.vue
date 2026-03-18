<template>
    <section class="space-y-6">
        <header class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-semibold">Список автомобилей</h1>
        </header>

        <div class="flex flex-wrap items-center gap-3">
            <input
                v-model="query.search"
                @input="onSearchInput"
                type="text"
                placeholder="Поиск по модели/бренду…"
                class="w-64 rounded border px-3 py-1.5"
            />

            <label class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Год от</span>
                <select
                    v-model.number="query.yearFrom"
                    @change="fetchList"
                    class="rounded border px-2 py-1.5"
                >
                    <option :value="null">Любой</option>
                    <option v-for="y in years" :key="'from-' + y" :value="y">{{ y }}</option>
                </select>
            </label>

            <label class="flex items-center gap-2">
                <span class="text-sm text-gray-600">до</span>
                <select
                    v-model.number="query.yearTo"
                    @change="fetchList"
                    class="rounded border px-2 py-1.5"
                >
                    <option :value="null">Любой</option>
                    <option v-for="y in years" :key="'to-' + y" :value="y">{{ y }}</option>
                </select>
            </label>

            <label class="flex items-center gap-2">
                <span class="text-sm text-gray-600">На странице</span>
                <select
                    v-model.number="query.perPage"
                    @change="onPerPageChange"
                    class="rounded border px-2 py-1.5"
                >
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                </select>
            </label>

            <button
                class="rounded border px-3 py-1.5 hover:bg-gray-50"
                :disabled="loading"
                @click="fetchList"
            >
                Обновить
            </button>
        </div>

        <div class="overflow-x-auto rounded border">
            <table class="min-w-full divide-y">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">
                        <button class="inline-flex items-center gap-1" @click="sortBy('id')">
                            ID
                            <span v-if="query.sort === 'id'">{{ query.dir === 'asc' ? '▲' : '▼' }}</span>
                        </button>
                    </th>
                    <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">
                        <button class="inline-flex items-center gap-1" @click="sortBy('brand')">
                            Бренд
                            <span v-if="query.sort === 'brand'">{{ query.dir === 'asc' ? '▲' : '▼' }}</span>
                        </button>
                    </th>
                    <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">
                        <button class="inline-flex items-center gap-1" @click="sortBy('model')">
                            Модель
                            <span v-if="query.sort === 'model'">{{ query.dir === 'asc' ? '▲' : '▼' }}</span>
                        </button>
                    </th>
                    <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">
                        <button class="inline-flex items-center gap-1" @click="sortBy('votes_count')">
                            Количество голосов
                            <span v-if="query.sort === 'votes_count'">{{ query.dir === 'asc' ? '▲' : '▼' }}</span>
                        </button>
                    </th>
                    <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">
                        <button class="inline-flex items-center gap-1" @click="sortBy('brand_votes_count')">
                            Количество голосов за марку
                            <span v-if="query.sort === 'brand_votes_count'">{{ query.dir === 'asc' ? '▲' : '▼' }}</span>
                        </button>
                    </th>
                    <th class="px-3 py-2 text-left text-sm font-medium text-gray-700">
                        <button class="inline-flex items-center gap-1" @click="sortBy('year')">
                            Год
                            <span v-if="query.sort === 'year'">{{ query.dir === 'asc' ? '▲' : '▼' }}</span>
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y">
                <tr v-if="loading">
                    <td colspan="5" class="px-3 py-6 text-center text-gray-500">Загрузка…</td>
                </tr>

                <tr v-else-if="!cars.length">
                    <td colspan="5" class="px-3 py-6 text-center text-gray-500">Ничего не найдено</td>
                </tr>

                <tr v-else v-for="row in cars" :key="row.id" class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ row.id }}</td>
                    <td class="px-3 py-2 text-sm text-gray-800">
                        {{ row.brand?.name ?? row.brand ?? '—' }}
                    </td>
                    <td class="px-3 py-2 text-sm text-gray-800">{{ row.model }}</td>
                    <td class="px-3 py-2 text-sm text-gray-800">{{ row.votesCount }}</td>
                    <td class="px-3 py-2 text-sm text-gray-800">{{ row.brandVotesCount }}</td>
                    <td class="px-3 py-2 text-sm text-gray-800">{{ row.year }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="text-sm text-gray-600">
                Показаны
                <span class="font-medium">{{ pageFrom }}</span>–
                <span class="font-medium">{{ pageTo }}</span>
                из
                <span class="font-medium">{{ total }}</span>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="rounded border px-2 py-1.5"
                    :disabled="!hasPrev || loading"
                    @click="goPrev"
                >
                    ‹
                </button>
                <span class="text-sm">
          Стр.
          <input
              class="mx-1 w-16 rounded border px-2 py-1 text-center"
              type="number"
              min="1"
              :max="totalPages"
              v-model.number="query.page"
              @change="onPageInput"
          />
          из {{ totalPages }}
        </span>
                <button
                    class="rounded border px-2 py-1.5"
                    :disabled="!hasNext || loading"
                    @click="goNext"
                >
                    ›
                </button>
            </div>
        </div>
    </section>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import {mapActions} from "pinia";
import {useCarsStore} from "../Stores/cars.js";

const SortIcon = {
    name: 'SortIcon',
    props: {
        active: { type: Boolean, default: false },
        dir: { type: String, default: 'asc' },
    },
    render() {
        if (!this.active) {
            return this.$slots.default?.()
        }
        return this.dir === 'asc'
            ? this.$createElement('span', { class: 'text-gray-500' }, '▲')
            : this.$createElement('span', { class: 'text-gray-500' }, '▼')
    },
}

export default {
    name: 'Rating',
    components: { Link, SortIcon },

    data() {
        const min = 1970
        const years = []
        for (let y = new Date().getFullYear(); y >= min; y--) years.push(y)

        return {
            years,
            cars: [],
            total: 0,
            loading: false,
            query: {
                search: '',
                page: 1,
                perPage: 10,
                sort: 'created_at',
                dir: 'desc',
            },
            pageFrom: 0,
            pageTo: 0,
            debouncedFetch: null,
        }
    },

    computed: {
        totalPages() {
            return Math.max(1, Math.ceil(this.total / this.query.perPage))
        },
        hasPrev() {
            return this.query.page > 1
        },
        hasNext() {
            return this.query.page < this.totalPages
        },
    },

    watch: {
        'query.page': 'fetchList',
        'query.perPage': 'fetchList',
        'query.sort': 'fetchList',
        'query.dir': 'fetchList',
    },

    created() {
        this.debouncedFetch = this.debounce(this.fetchList, 350)
    },

    mounted() {
        this.fetchList()
    },

    methods: {
        ...mapActions(useCarsStore, ['getCarsList']),
        async fetchList() {
            this.loading = true

            this.cars = await this.getCarsList({
                offset: this.query.page * this.query.perPage,
                limit: this.query.perPage,
                model: this.query.search,
                yearFrom: this.query.yearFrom,
                yearTo: this.query.yearTo,
                orderBy: this.query.sort,
                orderDirection: this.query.dir,
            })

            this.loading = false
        },

        onSearchInput() {
            this.query.page = 1
            this.debouncedFetch()
        },

        onPerPageChange() {
            this.query.page = 1
        },

        onPageInput() {
            if (!Number.isFinite(this.query.page) || this.query.page < 1) {
                this.query.page = 1
            }
            if (this.query.page > this.totalPages) {
                this.query.page = this.totalPages
            }
        },

        goPrev() {
            if (this.hasPrev) this.query.page -= 1
        },

        goNext() {
            if (this.hasNext) this.query.page += 1
        },

        sortBy(field) {
            if (this.query.sort === field) {
                this.query.dir = this.query.dir === 'asc' ? 'desc' : 'asc'
            } else {
                this.query.sort = field
                this.query.dir = 'asc'
            }
        },

        debounce(fn, wait = 300) {
            let timeout
            const ctx = this
            return function (...args) {
                clearTimeout(timeout)
                timeout = setTimeout(() => fn.apply(ctx, args), wait)
            }
        },
    },
}
</script>

<style scoped>
</style>
