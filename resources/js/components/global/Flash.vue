<template>                  
    <div>
        <div 
          class="flash"
          :class="flash_type"
          @click="$emit('hide_flash')"
          >
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <div class="flash_circle">
                <div class="in" v-if="flash_type === 'success'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                      <path d="M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z"  stroke-miterlimit="10" stroke-width="32"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 192L256.13 320l-47.95-48M191.95 320L144 272M305.71 192l-51.55 59"/>
                    </svg>
                </div>
                <div class="flash_circle_in" v-else-if="flash_type === 'danger'">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z" stroke-miterlimit="10" stroke-width="32"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M320 320L192 192M192 320l128-128"/>
                  </svg>
                </div>                  
                <div class="in" v-else="flash_type === 'info'">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                      <path d="M248 64C146.39 64 64 146.39 64 248s82.39 184 184 184 184-82.39 184-184S349.61 64 248 64z" stroke-miterlimit="10" stroke-width="32"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M220 220h32v116"/><path stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M208 340h88"/>
                      <path d="M248 130a26 26 0 1026 26 26 26 0 00-26-26z"/>
                  </svg>                                                    
                </div>			
            </div>
            <div class="flash_content">
                <div v-if="title" class="flash_content_title" v-html="title"></div>
                <div v-if="text" class="flash_content_text" v-html="text"></div> 
            </div>				
        </div>
    </div>
</template>

<script>
export default {
    props: ['flash_type','title','text'],
    data(){
        return{
            availableFlashes: ['success','danger','info'],
        }
    }
}
</script>
<style scoped lang="scss">
		
		.flash{
			display: flex;
			justify-content: space-between;
			align-items: center;
			border-radius: 2px;
			border-radius: 3px;
			padding: 1em;
			min-width: 250px;
			position: relative;
			transition: all .3s;
			cursor: pointer;                        
            max-width: 400px;
            margin: 0 auto;
            
            svg{
                width: 30px;
                height: 30px;
                fill: none;
            }

            &.success{
                color: #009688;

                span{
                    border-top: 1px solid #00808021;
			        border-left: 1px solid #00808021;
                }

                svg{
                  stroke: #009688;                 
                }
            }

             &.danger{
                color: #e91e63;

                span{
                    border-top: 1px solid #e91e6324;
			        border-left: 1px solid #e91e6324;
                }

                svg{
                    stroke: #e91e63;             
                }
            }

            &.info{
                color: #3f51b5;

                span{
                    border-top: 1px solid #3f51b526;
			        border-left: 1px solid #3f51b526;
                }

                svg{
                    stroke: #3f51b5;                 
                }
            }

            &_circle{
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 10px;

                &_in{
                    font-size: 18px;			
                }
            }


            &_content{
                flex-basis: 70%;
                display: flex;
                flex-direction: column;
                padding: 0 1em;

                &_title{
                    font-size: 1.3em;		
                    font-variant: small-caps;
                    letter-spacing: 1px;
                    white-space: nowrap;	
                    font-weight: 600;
                }

                &_text{
                    white-space: nowrap;
                    font-size: .8em;
                }

            }


            span{

                position: absolute;
			    display: block;	

                &:nth-of-type(1){
                    left: 0;
                    top: 0;
                    height: 20%;	
                }
                &:nth-of-type(2){
                    left: 0;
                    top: 0;
                    width: 5%;	
                }
                &:nth-of-type(3){
                    left: 0;
                    bottom: 0;
                    width: 5%;	
                }
                &:nth-of-type(4){
                    left: 0;
                    bottom: 0;
                    height: 20%;	
                }
                &:nth-of-type(5){
                    right: 0;
                    top: 0;
                    height: 20%;
                }
                &:nth-of-type(6){
                    right: 0;
                    top: 0;
                    width: 5%;	
                }
                &:nth-of-type(7){
                    right: 0;
                    bottom: 0;
                    height: 20%;
                }
                &:nth-of-type(8){
                    right: 0;
                    bottom: 0;
                    width: 5%;	
                }
            }

		}

	
</style>