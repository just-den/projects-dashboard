<template>                  
    <div class="multiselect">
        <div 
            class="multiselect_input_wrap no-close"
            @click="toggleList(true,$event.target)"
        >
            <div class="input no-close">
                <div
                    v-for="activeTag in active_tags"                  
                    :style="{backgroundColor: activeTag.color.name}"
                    class="no-close"
                >
                    <span 
                        class="no-close"
                        @click = "selectTag(activeTag,false)"
                    >x</span>
                    <span  
                        class="no-close"
                        v-text="activeTag.name"
                    ></span>
                </div>
            </div>
            <div 
                class="placeholder"
                v-if="active_tags.length === 0"
            >
                {{placeholder}}
            </div>
            <nav class="no-close">
                <svg class="no-close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path class="no-close" d="M98 190.06l139.78 163.12a24 24 0 0036.44 0L414 190.06c13.34-15.57 2.28-39.62-18.22-39.62h-279.6c-20.5 0-31.56 24.05-18.18 39.62z"/>
                </svg>
            </nav>
        </div>
        <transition name="animation-list">
            <div 
                class="multiselect_list no-close"
                v-if="list"
            >
                <div 
                    
                    class="multiselect_list_option no-close"
                    v-for="tag in tags"
                    @click = "selectTag(tag,true)"
                >
                    <div class="multiselect_list_option_check no-close">
                        <label>
                            <input type="checkbox" class="no-close">
                            <svg 
                                v-if="tag.status"
                                class="no-close"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path class="no-close" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/>
                            </svg>
                        </label>
                    </div>
                    <div 
                        class="multiselect_list_option_content no-close"
                    >
                        <span v-text="tag.name" class="no-close"></span>
                    </div>
                </div>
            </div>       
        </transition>	
    </div>
</template>

<script>
export default {
    props: ['tags','active_tags'],
    data(){
        return{
           list: false,
           placeholder: 'Choose tag',
        }
    },
    methods:{
        toggleList(v,target=''){
            if(target.tagName !== 'SPAN'){
                this.list = !v ? false : !this.list
            }           
        },
        selectTag(activeTag,status){  
            console.log(activeTag,status)         
            this.$emit('select_tag',activeTag,status)
        },
    },
    created(){
        this.$parent.$on('closeMultiselectList', this.toggleList)
    }
}
</script>

<style scoped lang="scss">
        
    @import '~@/sass/abstracts/_mixins.scss';
    @import '~@/sass/abstracts/_variables.scss';

    .multiselect{
        display: flex;
        flex-direction: column;
        min-width: 265px;
        position: relative;
        z-index: 1;
        bottom: 0;
        right: 0;
        background: white;


        &_input_wrap{
            width: 100%;
            height: 40px;
            position: relative;

            .input{
                width: 100%;
                height: 100%;
                border: 1px solid $gray__1;
                border-radius: 3px;
                display: flex;
                padding-left: 5px;
                padding-right: 45px;
                font-size: .85em;
                align-items: center;

                div{
                    border-radius: 3px;
                    @include flex-between;
                    height: 65%;
                    margin-right: 5px;

                    span{

                        &:first-of-type{
                            cursor: pointer;

                            &:hover{
                                background-color: $gray__2;
                            }
                        }

                        color: white;
                        padding: 2px 8px;
                        white-space: nowrap;
                    }
                }
            }

            .placeholder{
                position: absolute;
                top: 50%;
                left: 20px;
                transform: translateY(-50%);
                font-size: .9em;
                color: $gray__3;
                z-index: -1;
            }

            nav{
                position: absolute;
                top: 0;
                right: 0;
                width: 40px;
                height: 100%;
                cursor: pointer;
                transition: background-color .3s;
                @include flex-center;

                &:hover{
                    background-color: $gray__1;
                }

                svg{
                    fill: $gray__2;
                    width: 40%;
                    height: 40%;
                }
            }
        }

        &_list{

            padding-top: 15px;
            box-shadow: 0 3px 3px $gray__2;
            padding: .5em;
            position: absolute;
            background: white;
            left: 0;
            top: 100%;
            width: 100%;
            z-index: 1;
            max-height: 300px;
            overflow-y: auto;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;

            &_option{
                @include flex-between;
                padding: .5em .3em;
                cursor: pointer;
                height: 30px;
                transition: all .3s;
                margin-top: 3px;

                &_check{
                    width: 30px;
                    height: 30px;
                    @include flex-center;
                    border: 1px solid $gray__1;
                    border-radius: 7px;
                    padding: .3em;
                    z-index: -1;
                    
                    label{
                        @include flex-center;
                        width: 100%;
                        height: 100%;
                        position: relative;
                        margin-bottom: 0;

                        input[type=checkbox]{
                            opacity: 0;
                            visibility: hidden;
                            position: absolute;
                            z-index: 0;
                        }

                        svg{
                            width: 100%;
                            height: 100%;
                            stroke: $gray__3;
                            fill: none;
                            @include translate-center;
                            z-index: 1;
                            cursor: pointer;
                        }
                    }
                }

                &_content{
                    flex-basis: 80%;
                    display: flex;
                    align-items: center;
                    margin-left: 20px;
                    z-index: 1;

                    span{
                        padding: 2px 10px;
                        color: $gray__3;
                        white-space: nowrap;
                    }

                }
            }
        }

    }
	
</style>