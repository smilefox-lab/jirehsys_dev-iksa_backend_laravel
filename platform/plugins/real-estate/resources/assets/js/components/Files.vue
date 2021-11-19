

<template>
    <div class="d-flex flex-column justify-content-start align-items-center">
        <label :for="inputFileName" class="cursor-pointer">
            <input :name="inputFileName" type="file" :id="inputFileName" multiple @change="onChange">
            <div>Agregar</div>
        </label>

        <section class="container-preview" :class="{ 'container-preview-alone': isShow }">
            <div v-for="(file, key) in draftFiles" :key="`draftFiles${key}`" class="d-flex flex-row justify-content-start align-items-center preview">
                <img src="/vendor/core/images/files.svg" />
                <div class="file-name">{{ fileName(file) }}</div>
                <div class="destroy-files" @click="destroy(key)"><i class="fa fa-trash"></i></div>
                <input type="hidden" :name="inputHiddenName" :value="file">
            </div>
            <div v-for="(file, key) in newFiles" :key="`newFiles${key}`" class="d-flex flex-row justify-content-start align-items-center preview">
                <img src="/vendor/core/images/files.svg" />
                <div class="file-name">{{ file.name }}</div>
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
        props: ['files', 'inputFileName', 'inputHiddenName'],
        data() {
            return {
                isLoading: true,
                draftFiles: [],
                newFiles: [],
            };
        },
        mounted() {
            this.draftFiles = JSON.parse(this.files);
        },
        computed: {
            isShow() {
                return !this.draftFiles?.length > 0 && !this.newFiles?.length > 0;
            },
        },
        methods: {
            onChange(e) {
                const selectedFiles = Array.from(e.target.files);
                if (this.newFiles.length > 0) {
                    this.newFiles = [];
                }

                for (let i = 0; i < selectedFiles.length; i++) {
                    this.newFiles.push(selectedFiles[i]);
                }
            },
            destroy(index) {
                this.draftFiles.splice(index, 1);
            },
            fileName(file) {
                const name = file.split('/');
                return name[name.length - 1];
            }
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
        flex-flow: column;
        justify-content: flex-start;

        .preview {
            position: relative;
            margin-bottom: 0.5em;

            img {
                width: 20px;
                margin-right: 0.5em;
            }

            .file-name {
                width: 100%;
                overflow-wrap: break-word;
                overflow: hidden;
            }

            .destroy-files {
                cursor: pointer;
                width: 20px;
                color: #fd413c;
                margin-left: 0.5em;
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
