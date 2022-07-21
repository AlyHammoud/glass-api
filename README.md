<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).






<template>
    <div class="">
        <page-layout>
            <template v-slot:title>
                <!-- content for the header slot -->
                <p>Products</p>
            </template>
            <template v-slot:images>
                <div class="servicesTag">
                    <div>
                        <input
                            id="all"
                            type="checkbox"
                            @change="addToQuery('all')"
                            :checked="servicesNames.length ? false : true"
                        />
                        <label for="all"> All </label>
                    </div>
                    <!-- <br /> -->

                    <div
                        class=""
                        v-for="(service, index) in store.state.servicesNames"
                        :key="index"
                    >
                        <input
                            :id="service"
                            type="checkbox"
                            @change="addToQuery(service)"
                            :checked="
                                servicesNames.includes(service) ? true : false
                            "
                        />
                        <label :for="service"> {{ service }} </label>
                        <!-- <br /> -->
                    </div>
                </div>
                <Loader v-if="isLoading" />
                <div class="products" v-if="hasProducts">
                    <div
                        class="product-card"
                        v-for="product in store.state.paginateProducts.data"
                        :key="product.id"
                        @click="goTo('SingleProductPage', product.slug)"
                    >
                        <div class="product-card-image">
                            <img :src="product.cover" alt="" />
                        </div>

                        <div class="product-card-body">
                            <div class="tag tag-purple">
                                {{ product.service[0].name }}.
                            </div>

                            <div class="title">
                                <p>{{ product.title }}</p>
                            </div>

                            <div class="details">
                                {{ product.briefDetails }}
                            </div>

                            <div class="info">{{ product.created_at1 }}</div>
                        </div>
                    </div>
                </div>
                <div style="text-align: center" v-else>
                    <!-- No Products were found -->
                    {{ noProductsFound }}
                </div>
                <div class="pagination" v-if="hasProducts">
                    <div
                        class="prev"
                        @click="paginatePrev"
                        v-if="store.state.paginateProducts.current_page > 1"
                    >
                        <font-awesome-icon
                            class=""
                            :icon="['fa', 'circle-chevron-left']"
                        />
                    </div>

                    <div v-if="store.state.paginateProducts.last_page <= 6">
                        <div
                            class="pages"
                            v-for="page in store.state.paginateProducts
                                .last_page"
                            @click="goToPage(page)"
                            :key="page"
                            :style="{
                                'background-color':
                                    store.state.paginateProducts.current_page ==
                                    page
                                        ? 'rgba(30, 92, 84, 0.8)'
                                        : 'rgb(30, 92, 84)',
                            }"
                        >
                            <p>{{ page }}</p>
                        </div>
                    </div>

                    <div v-else>
                        <div
                            class="pages"
                            @click="goToPage(1)"
                            :style="{
                                'background-color':
                                    store.state.paginateProducts
                                        .current_page === 1
                                        ? 'rgba(30, 92, 84, 0.8)'
                                        : 'rgb(30, 92, 84)',
                            }"
                        >
                            <p>1</p>
                        </div>

                        <div
                            v-if="
                                store.state.paginateProducts.current_page <= 4
                            "
                        >
                            <div
                                class="pages"
                                v-for="(page, index) in 3"
                                @click="goToPage(index + 2)"
                                :key="index"
                                :style="{
                                    'background-color':
                                        store.state.paginateProducts
                                            .current_page ===
                                        index + 2
                                            ? 'rgba(30, 92, 84, 0.8)'
                                            : 'rgb(30, 92, 84)',
                                }"
                            >
                                <p>{{ index + 2 }}</p>
                            </div>
                            ...
                        </div>

                        <div
                            v-else-if="
                                store.state.paginateProducts.current_page > 4 &&
                                store.state.paginateProducts.current_page <
                                    store.state.paginateProducts.last_page - 1
                            "
                        >
                            <p>...</p>
                            <p
                                class="pages"
                                :style="{
                                    'background-color':
                                        store.state.paginateProducts
                                            .current_page ===
                                        store.state.paginateProducts
                                            .current_page -
                                            1
                                            ? 'rgba(30, 92, 84, 0.8)'
                                            : 'rgb(30, 92, 84)',
                                }"
                                @click="
                                    goToPage(
                                        store.state.paginateProducts
                                            .current_page - 1
                                    )
                                "
                            >
                                {{
                                    store.state.paginateProducts.current_page -
                                    1
                                }}
                            </p>
                            <p
                                class="pages"
                                :style="{
                                    'background-color':
                                        store.state.paginateProducts
                                            .current_page ===
                                        store.state.paginateProducts
                                            .current_page
                                            ? 'rgba(30, 92, 84, 0.8)'
                                            : 'rgb(30, 92, 84)',
                                }"
                                @click="
                                    goToPage(
                                        store.state.paginateProducts
                                            .current_page
                                    )
                                "
                            >
                                {{ store.state.paginateProducts.current_page }}
                            </p>
                            <p
                                class="pages"
                                :style="{
                                    'background-color':
                                        store.state.paginateProducts
                                            .current_page ===
                                        store.state.paginateProducts
                                            .current_page +
                                            1
                                            ? 'rgba(30, 92, 84, 0.8)'
                                            : 'rgb(30, 92, 84)',
                                }"
                                @click="
                                    goToPage(
                                        store.state.paginateProducts
                                            .current_page + 1
                                    )
                                "
                            >
                                {{
                                    store.state.paginateProducts.current_page +
                                    1
                                }}
                            </p>
                            <p>...</p>
                        </div>

                        <div v-else>
                            <p>...</p>
                            <p
                                class="pages"
                                :style="{
                                    'background-color':
                                        store.state.paginateProducts
                                            .current_page ===
                                        store.state.paginateProducts.last_page -
                                            2
                                            ? 'rgba(30, 92, 84, 0.8)'
                                            : 'rgb(30, 92, 84)',
                                }"
                                @click="
                                    goToPage(
                                        store.state.paginateProducts.last_page -
                                            2
                                    )
                                "
                            >
                                {{ store.state.paginateProducts.last_page - 2 }}
                            </p>
                            <p
                                class="pages"
                                :style="{
                                    'background-color':
                                        store.state.paginateProducts
                                            .current_page ===
                                        store.state.paginateProducts.last_page -
                                            1
                                            ? 'rgba(30, 92, 84, 0.8)'
                                            : 'rgb(30, 92, 84)',
                                }"
                                @click="
                                    goToPage(
                                        store.state.paginateProducts.last_page -
                                            1
                                    )
                                "
                            >
                                {{ store.state.paginateProducts.last_page - 1 }}
                            </p>
                        </div>

                        <!-- <div  
                            v-for="(page, index) in store.state.paginateProducts.last_page-2"
                            @click="goToPage(index+2)"
                            :key="index"
                        >
                           <p>{{index+2}}</p>
                        </div> -->

                        <div
                            class="pages"
                            @click="
                                goToPage(store.state.paginateProducts.last_page)
                            "
                            :style="{
                                'background-color':
                                    store.state.paginateProducts.current_page ==
                                    store.state.paginateProducts.last_page
                                        ? 'rgba(30, 92, 84, 0.8)'
                                        : 'rgb(30, 92, 84)',
                            }"
                        >
                            <p>
                                {{ store.state.paginateProducts.last_page }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="next"
                        @click="paginateNext"
                        v-if="
                            route.query.page <
                            store.state.paginateProducts.last_page
                        "
                    >
                        <font-awesome-icon
                            class=""
                            :icon="['fa', 'circle-chevron-right']"
                        />
                    </div>
                </div>
            </template>
        </page-layout>
    </div>
</template>

<script setup>
import PageLayout from "../components/PageLayout.vue";
import { useRouter, useRoute } from "vue-router";
import {
    onMounted,
    ref,
    watch,
    computed,
    watchEffect,
} from "@vue/runtime-core";
import { useStore } from "vuex";
import Loader from "../components/AddOns/Loader.vue";

const router = useRouter();
const route = useRoute();
const store = useStore();

const page = ref(null);
const hasProducts = ref(false);
const isLoading = ref(true);

const noProductsFound = ref(null);

if (route.query.page == null) {
    route.query.page = 1;
}

function paginateNext() {
    page.value = Number(route.query.page) + 1;
}

function paginatePrev() {
    page.value = Number(route.query.page) - 1;
}

function goToPage(page1) {
    page.value = page1;
}

const getAllByServicesNames = ref([
    "Secorete",
    "Showers",
    "Handrills",
    "Facades",
    "Partitions",
    "Mirrors",
]);
const servicesNames = ref([]);

if(route.query.servicesNames !== null){ 
    addToQuery(route.query.servicesNames)
}

function addToQuery(service) {
    isLoading.value = true;

    if (servicesNames.value.includes(service)) {
        servicesNames.value = servicesNames.value.filter(
            (serviceName) => serviceName != service
        );
        page.value = 1; 
    } else {
        servicesNames.value.push(service);
        page.value = 1;
    }

    if (service === "all") {
        servicesNames.value = [];
        page.value = 1; 
    }

    router
        .push({ name: "ProductsPage", query: { page: page.value } })
        .then(() => {
            isLoading.value = true;
            store
                .dispatch("getPaginateProducts", [
                    1,
                    servicesNames.value.length
                        ? servicesNames.value
                        : getAllByServicesNames.value,
                ])
                .then(() => {
                    isLoading.value = false;
                    if (store.state.paginateProducts.data.length) {
                        hasProducts.value = true;
                    }else{
                        hasProducts.value = false;
                        noProductsFound.value = "No products were found!"
                    }
                })
                .catch(() => (isLoading.value = false));
        });
}


onMounted(() => {
    store
        .dispatch("getPaginateProducts", [
            route.query.page,
            getAllByServicesNames.value,
        ])
        .then(() => {alert("mounted: "+route.query.page)
            isLoading.value = false;
            if (store.state.paginateProducts.data.length) {
                hasProducts.value = true;
            }else{
                noProductsFound.value = "No products were found!"
            }
        })
        .catch(() => (isLoading.value = false));

        addToQuery('all');
});

watch(page, (newVal, oldVal) => { 
    router.push({ name: "ProductsPage", query: { page: newVal } }).then(() => {
        isLoading.value = true;
        store
            .dispatch("getPaginateProducts", [
                newVal,
                servicesNames.value.length
                    ? servicesNames.value
                    : getAllByServicesNames.value,
            ])
            .then(() => {
                isLoading.value = false;
                if (store.state.paginateProducts.data.length) {
                    hasProducts.value = true;
                }
            })
            .catch(() => (isLoading.value = false));
    });
});

function goTo(link, slug) {
    router.push({ name: link, params: { slug: slug } });
}

</script>

<style lang="scss" scoped>
.pagination {
    display: flex;
    flex-direction: row;
    height: 4rem;
    width: auto;
    justify-content: center;
    align-items: center;
    margin: auto;

    .next,
    .prev {
        font-size: 1.5rem;
        margin: 0 20px;
        cursor: pointer;
        color: rgb(30, 92, 84);
    }

    div {
        width: auto;
        display: flex;
        align-items: center;
        justify-content: center;

        .pages {
            background-color: rgb(30, 92, 84);
            color: #fff;
            width: auto;
            max-width: 100%;
            height: 1.5rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            cursor: pointer;
            border: 0.5px solid white;
        }
    }
}
.products {
    width: 80%;
    display: flex;
    justify-content: center;
    margin: 0 auto;
    padding-bottom: 40px;
    flex-wrap: wrap;

    @media (max-width: 800px) {
        width: 98%;
    }

    .product-card {
        margin: 10px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        min-width: 300px;
        width: 300px;
        cursor: pointer;

        @media (max-width: 645px) {
            min-width: 220px;
            width: 220px;
        }

        &:hover {
            transform: scale(1.05) rotate(2deg);
        }

        &-image {
            width: 100%;
            height: 200px;

            img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: 50% 50%;
            }
        }

        &-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;

            .tag {
                background: #cccccc;
                border-radius: 50px;
                font-size: 12px;
                margin: 0;
                color: #fff;
                padding: 2px 10px;
                text-transform: uppercase;
                cursor: pointer;
            }

            .tag-purple {
                background-color: #5e76bf;
            }

            .title {
                margin: 10px 0 20px;

                p {
                    font-size: 19px;
                    font-weight: 500;
                }
            }

            .details {
                margin: 0 0 20px;
            }

            .info {
                align-self: flex-end;
                font-size: 10px;
                color: gray;
            }
        }
    }
}

.servicesTag {
    display: flex;
    flex-direction: row;
    width: 80%;
    justify-content: center;
    align-items: center;
    margin: 15px auto;
    flex-wrap: wrap;

    div {
        margin-top: 15px;

        label {
            margin-right: 20px;
            cursor: pointer;
            color: #fff;
            background-color: rgb(30, 92, 84);
            padding: 1px 5px;
            border-radius: 5px;

            &:hover {
                background-color: rgb(143, 202, 143);
            }

            @media (hover: none) {
                &:hover {
                    background-color: rgb(30, 92, 84);
                }
            }
        }

        input[type="checkbox"] {
            display: none;

            &:checked ~ label {
                border: 1px solid rgba(81, 203, 238, 1);
                box-shadow: 0 0 5px rgba(81, 203, 238, 1);
                background-color: rgb(143, 202, 143);
            }
        }
    }
}
</style>
