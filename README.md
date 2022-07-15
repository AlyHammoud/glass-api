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
    <div class="image-viewer-wrapper">
        <div class="image-viewer">
            <img
                v-for="(image, index) in props.images"
                :key="index"
                v-lazy="{
                    src: image.name,
                    loading:
                        'https://upload.wikimedia.org/wikipedia/commons/b/b1/Loading_icon.gif',
                    delay: 500,
                }"
                lazy="loading"
                @click="showImage(index)"
            />
        </div>
        <p class="view-all" v-if="!hideViewMore" @click="sendIncrementPage">
            View More
        </p>

        <div
            class="viewImage"
            @click.self="imageIndex = false"
            v-if="imageIndex"
        >
            <img
                :class="{ viewImage: imageIndex, zoomIn: zoomIn }"
                :src="imageIndex"
                alt=""
            />
            <div @click="imageIndex = false" class="close-image">
                <font-awesome-icon :icon="['fas', 'circle-xmark']" />
            </div>
            <div class="zoom" @click="zoom">
                <font-awesome-icon :icon="['fas', zoomInOut]" />
            </div>
            <div
                :disabled="showNext"
                class="next"
                @click="next"
                :style="{
                    background: !showNext ? 'rgb(0,0,0,0.8)' : '',
                    color: !showNext ? '#fff' : '',
                }"
            >
                <font-awesome-icon :icon="['fas', 'angle-right']" />
            </div>
            <div
                :disabled="showPrev"
                class="prev"
                @click="prev"
                :style="{
                    background: !showPrev ? 'rgb(0,0,0,0.8)' : '',
                    color: !showPrev ? '#fff' : '',
                }"
            >
                <font-awesome-icon :icon="['fas', 'angle-left']" />
            </div>

            <div class="slider">
                <img
                    v-for="(image, index) in props.images"
                    :key="index"
                    :src="image.name"
                    :style="{
                        opacity: index === index1 ? 1 : 0.3,
                        boder: index === index1 ? '2px solid gray' : '',
                    }"
                    @click="showImage(index)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "@vue/runtime-core";
import { useStore } from "vuex";

const store = useStore();
// const props = defineProps(["images"]);
const props = defineProps({
    images: {
        type: Array,
    },
    hideViewMore: {
        type: Boolean,
        default: false,
    },
});
const emit = defineEmits(["pageIncrement"]);

const imageIndex = ref(false);
const index1 = ref(null);
const zoomIn = ref(false);
const zoomInOut = ref("magnifying-glass-plus");

const showPrev = ref(true);
const showNext = ref(true);
const opacityImage = ref(0.8);

const page = ref(1);

function sendIncrementPage() {
    page.value = page.value + 1;
    emit("pageIncrement", page);
}

watch(zoomIn, (newVal, oldVal) => {
    if (newVal) {
        zoomInOut.value = "magnifying-glass-minus";
    }
    // }else{
    //     zoomInOut.value = 'magnifying-glass-plus';
    // }
    if (oldVal) {
        zoomInOut.value = "magnifying-glass-plus";
    }
});

const showImage = (index) => {
    imageIndex.value = props.images[index].name;

    index1.value = index;

    if (index1.value == props.images.length - 1) {
        showNext.value = false;
        showPrev.value = true;
    } else if (index1.value == 0) {
        showNext.value = true;
        showPrev.value = false;
    } else {
        showNext.value = true;
        showPrev.value = true;
    }
};

function next() {
    if (index1.value == props.images.length - 3) {
        page.value = page.value + 1;
        emit("pageIncrement", page);
    }

    if (index1.value < props.images.length - 1) {
        imageIndex.value = props.images[index1.value + 1].name;
        index1.value++;
    }

    if (index1.value == props.images.length - 1) {
        showNext.value = false;
        showPrev.value = true;
    } else {
        showNext.value = true;
        showPrev.value = true;
    }
}

function prev() {
    if (index1.value > 0) {
        imageIndex.value = props.images[index1.value - 1].name;
        index1.value--;
    }

    if (index1.value == 0) {
        showNext.value = true;
        showPrev.value = false;
    } else {
        showNext.value = true;
        showPrev.value = true;
    }
}

function zoom() {
    zoomIn.value = !zoomIn.value;
}

window.addEventListener("keydown", (e) => {
    if (e.key == "Escape") {
        imageIndex.value = false;
    }
});
</script>

<style lang="scss">
.image-viewer-wrapper {
    width: 88%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin: 20px auto;
    border: 1px solid rgba(0, 0, 0, 0.2);
    padding: 10px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;

    @media screen and (max-width: 550px) {
        width: 95%;
    }

    .image-viewer {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(5, auto);
        grid-auto-rows: 150px 170px; // first, third, fifth... 150px,,//\\ 2nd, 4rth, 6ith.. 250px rows
        gap: 10px;
        grid-auto-flow: dense; //fill the empty spaces after putting grid-column

        @media screen and (max-width: 500px) {
            grid-auto-rows: 150px 150px;
        }

        img {
            border-radius: 8px;
            object-fit: cover;
            object-position: 50% 50%;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;

            &:nth-child(2) {
                grid-column: span 2;
            }

            &:nth-child(4) {
                grid-column: span 2;
                grid-row: span 2;
            }
            &:nth-child(9) {
                grid-column: span 2;
            }
            &:nth-child(13) {
                grid-column: span 2;
            }
            &:nth-child(19) {
                grid-row: span 2;
            }
        }
        @media (max-width: 1918px) {
            grid-template-columns: repeat(4, 1fr);
        }
        @media (max-width: 1500px) {
            grid-template-columns: repeat(3, 1fr);
        }
        @media (max-width: 800px) {
            grid-template-columns: repeat(2, 1fr);
        }

        img {
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 22;

            &:hover {
                opacity: 0.8;
                transform: scale(103%);
            }
        }
    }
    .view-all {
        text-align: center;
        margin-top: 25px;
        color: blue;
        cursor: pointer;
    }

    .viewImage {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);

        img {
            height: 90%;
            width: 80%;
            object-fit: contain;

            @media (min-width: 755px) {
                object-fit: cover;
            }
        }
        .zoomIn {
            object-fit: cover;
        }

        .close-image {
            position: absolute;
            font-size: 30px;
            color: rgb(145, 131, 131, 0.9);
            top: 10px;
            right: 14px;
            transition: all 0.5s ease;
            cursor: pointer;

            &:hover {
                color: rgba(235, 205, 205, 0.2);
            }
        }

        .zoom {
            position: absolute;
            font-size: 30px;
            color: rgba(221, 215, 215, 0.9);
            top: 80px;
            right: 14px;
            transition: all 0.5s ease;
            cursor: pointer;

            &:hover {
                color: rgba(235, 205, 205, 0.2);
            }
            @media (min-width: 755px) {
                display: none;
            }
        }

        .prev {
            font-size: 40px;
            position: absolute;
            top: 50%;
            left: 5%;
            background-color: rgb(0, 0, 0, 0.4);
            border-radius: 50%;
            width: 50px;
            text-align: center;
            transition: all 0.5s ease;
            z-index: 9999;
            cursor: pointer;

            &:hover {
                background-color: rgba(138, 135, 135, 0.4);
            }
        }
        .next {
            font-size: 40px;
            position: absolute;
            top: 50%;
            right: 5%;
            background-color: rgb(0, 0, 0, 0.4);
            border-radius: 50%;
            width: 50px;
            text-align: center;
            z-index: 9999;
            cursor: pointer;
            transition: all 0.5s ease;

            &:hover {
                background-color: rgba(138, 135, 135, 0.4);
            }
        }

        .slider {
            position: absolute;
            width: 70%;
            height: 50px;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 123123;
            background-color: rgb(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: auto;
            overflow-y: hidden;

            &::-webkit-scrollbar {
                height: 9px;
            }
            &::-webkit-scrollbar-track {
                background: #e4dcdc;
            }

            &::-webkit-scrollbar-thumb {
                background: rgb(78, 76, 76);
            }

            img {
                width: 60px;
                height: 90%;
                margin: 5px;
                opacity: 0.5;
                cursor: pointer;
            }
        }
    }
}
</style>







