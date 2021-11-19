

<template>
    <div class="d-flex flex-column justify-content-start align-items-center">
        <label for="image[]" class="cursor-pointer">
            <input name="image[]" type="file" id="image[]" multiple accept="image/*" @change="onFileChange">
            <div>Agregar imagenes</div>
        </label>
        <section class="container-preview" :class="{ 'container-preview-alone': isShow }">
            <img v-if="isShow" src="/vendor/core/images/placeholder.png" alt="" width="120">
            <div v-for="(image, key) in draft" :key="`images${key}`" class="d-flex flex-column justify-content-start align-items-center preview">
                <img :src="`/storage/${image}`" />
                <span class="destroy-images" @click="destroy(key)"><i class="fa fa-trash"></i></span>
                <input type="hidden" name="images[]" :value="image">
            </div>
            <div v-for="(image, key) in newImages" :key="`newImages${key}`" class="d-flex flex-column justify-content-start align-items-center preview">
                <img :ref="'image'" />
            </div>
        </section>
    </div>
</template>

<script>
    import { HalfCircleSpinner } from 'epic-spinners'

    export default {
        components: {
            HalfCircleSpinner
        },
        props: ['images'],
        data() {
            return {
                isLoading: true,
                draft: [],
                newImages: [],
                selectedFiles: null
            };
        },
        mounted() {
            this.draft = JSON.parse(this.images)
        },
        computed: {
            isShow() {
                return !this.draft.length > 0 && !this.newImages.length > 0;
            },
        },
        methods: {
            onFileChange(e) {
                this.selectedFiles = Array.from(e.target.files);
                if (this.newImages.length > 0) {
                    this.newImages = [];
                }

                for (let i = 0; i < this.selectedFiles.length; i++) {
                    this.newImages.push(this.selectedFiles[i]);
                }

                for (let i = 0; i < this.newImages.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.$refs.image[i].src = reader.result;
                    };

                    reader.readAsDataURL(this.newImages[i]);
                }
            },
            destroy(index) {
                this.draft.splice(index, 1);
            },
        }
    }
</script>

<style lang="scss" scoped>
    input[type="file"] {
        display: none;
    }

    label {
        position: absolute;
        top: 0;
        right: 0;
        line-height: 35px;
        height: 35px;
        margin-right: 10px;
        right: 0;
        font-weight: 600;
        color: #febc2c;
    }

    .container-preview {
        width: 100%;
        display: flex;
        flex-flow: row wrap;
        justify-content: flex-start;

        .preview {
            position: relative;
            width: 120px;
            height: 120px;
            flex: 0 0 calc((1/6)*100% - 8px);
            margin-right: 0.5em;
            margin-bottom: 0.5em;

            img {
                width: 100%;
                height: 100%;
            }

            .destroy-images {
                cursor: pointer;
                position: absolute;
                bottom: 0;
                color: #fd413c;

                &:hover {
                    opacity: 0.9;
                }
            }


        }
    }

    .container-preview-alone {
        justify-content: center !important;
    }


</style>
