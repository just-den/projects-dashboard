<template>
	<main
		:style="main_block_styles">
		<div class="title">
		    <h4>   	
		   		events  	
		    </h4>
		    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
		    	<path d="M34 20H14v4h20v-4zm4-14h-2V2h-4v4H16V2h-4v4h-2c-2.21 0-3.98 1.79-3.98 4L6 38c0 2.21 1.79 4 4 4h28c2.21 0 4-1.79 4-4V10c0-2.21-1.79-4-4-4zm0 32H10V16h28v22zM28 28H14v4h14v-4z"/>
		    </svg>
	    </div>
		<section class="sort">
		    <div>sort by: </div>
	        <div 
	        	@click="sortBy('term_id')"
	        	:class="{ active: sortedKey === 'term_id' }"
	        >
	        	<span>x</span>
	        	<span>term</span>
	        </div>
		</section>

	   <section class="events masonry" is="transition-group" name="animation-2-list">
		
	   	 <article 
	   	 	class="masonry-item"
 			v-for="event in eventsList"
			:class="{done: event.status}"
			:key="event.id"
		>
		    <div class="masonry-content">
			    <nav>
			  		<label :class="{ 'disabled': disabled }">
						<input 
	   						type="checkbox"
	   						:checked="event.status"
	   						@change="editEvent(
	   									event,			   									
	   									{ 
	   										status: $event.target.checked ? 1 : 0
	   									}
	   								)"
	   					>
	  					<svg v-if="event.status" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M464 128L240 384l-96-96M144 384l-96-96M368 128L232 284"/>
						</svg>
						<svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/>
						</svg>
			  		</label>		  			
			  		<div>
						<a 
							href="#" 
							@click.prevent="dataToEditor(event)"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M364.13 125.25L87 403l-23 45 44.99-23 277.76-277.13-22.62-22.62zM420.69 68.69l-22.62 22.62 22.62 22.63 22.62-22.63a16 16 0 000-22.62h0a16 16 0 00-22.62 0z"/>
							</svg>
						</a>
						<a 
							href="#"
							:class="{'disabled': disabled}"
			   				@click.prevent="deleteEvent(event.id)"
			   			>
							<svg 
								v-if="!deleting"
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/>
							</svg>
							<svg 
								v-else
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512"
								class="animation-rotation">
								<path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
							</svg>
						</a>		  			
			  		</div>				
			  </nav>		
<!-- 		      <h3 class="masonry-title">Nesciunt aspernatur eaque similique laudantium a</h3> -->
		      <div class="masonry-description" v-html="event.content"></div>
		      <div class="masonry-footer">
		    	<div class="categories">
		    		<span 
						v-text="event.category_name"
					></span>
		    	</div>
		    	<div class="tags">
		    		<span 
		    			style="background-color: rgba(0,0,0,.1); color: #444"
    					v-text="event.term_name"
					></span>
		    	</div>
		      </div>
		    </div>
		  </article>
		
	   </section>
		<section 
			class="text_editor"
			ref="editor_section"
		>
			<div class="message">
				<transition name="animation-list">
					<Flash 
						v-if="flash.status"
						:title="flash.title"
						:text="flash.text"
						:flash_type="flash.type"	
						@hide_flash="hideFlash"					
					></Flash>
				</transition>
			</div>
			<form>	
				<div class="form_group ">
					<div class="form_group_item">
						<select
							@change = "changeCategory($event.target)"
							ref="select_category"
							class="category_select"
							>
							<option value="0" disabled selected>category</option>
							<option 
								v-for="category in categories" 
								:value="category.id"
								v-text="category.name"
								class="category_option"
							></option>
						</select>
					</div>	
					<div class="form_group_item">
						<select
							@change = "changeTerm($event.target)"
							ref="select_term"
							class="term_select"
							>
							<option value="0" disabled selected>term</option>
							<option 
								v-for="term in terms" 
								:value="term.id"
								v-text="term.name"
								class="term_option"
							></option>
						</select>
					</div>							
				</div>		
				<ckeditor 
					v-model="editorData" 
					:config="editorConfig"
				></ckeditor>
				<div v-if="validation">
					<transition	mode="out-in">
						<button
							:class="{'disabled': disabled || !editorData}"
							@click.prevent="addEvent()"
							v-if="!editData"						
							key="add"
						>
							<svg 
								v-if="!ajaxMarker"
								xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
								<path d="M18 32.34L9.66 24l-2.83 2.83L18 38l24-24-2.83-2.83z"/>
							</svg>
							<svg 
								v-else
								class="animation-rotation"
								xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
								<path d="M24 8V2l-8 8 8 8v-6c6.63 0 12 5.37 12 12 0 2.03-.51 3.93-1.39 5.61l2.92 2.92C39.08 30.05 40 27.14 40 24c0-8.84-7.16-16-16-16zm0 28c-6.63 0-12-5.37-12-12 0-2.03.51-3.93 1.39-5.61l-2.92-2.92C8.92 17.95 8 20.86 8 24c0 8.84 7.16 16 16 16v6l8-8-8-8v6z"/>
							</svg>
						</button>
						<button
							:class="{'disabled': disabled}"
							@click.prevent="editEvent(
													currentEvent,
													{ 
														content: editorData 
													}
											)"
							v-else
							key="edit"
						>
							<svg 
								v-if="!ajaxMarker"
								xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
								<path d="M18 32.34L9.66 24l-2.83 2.83L18 38l24-24-2.83-2.83z"/>
							</svg>
							<svg 
								v-else
								class="animation-rotation"
								xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
								<path d="M24 8V2l-8 8 8 8v-6c6.63 0 12 5.37 12 12 0 2.03-.51 3.93-1.39 5.61l2.92 2.92C39.08 30.05 40 27.14 40 24c0-8.84-7.16-16-16-16zm0 28c-6.63 0-12-5.37-12-12 0-2.03.51-3.93 1.39-5.61l-2.92-2.92C8.92 17.95 8 20.86 8 24c0 8.84 7.16 16 16 16v6l8-8-8-8v6z"/>
							</svg>
						</button>
				   </transition>
			   		<button
					    key="remove"
					    @click.prevent="removeData()"
					 >
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
							<path d="M38 12.83L35.17 10 24 21.17 12.83 10 10 12.83 21.17 24 10 35.17 12.83 38 24 26.83 35.17 38 38 35.17 26.83 24z"/>
						</svg>
					</button>					
				</div>		
			</form>		
		</section>
	</main>	
</template>

<script>
import { Helper } from '@/js/utilities/helper'
export default {
	props: ['csrf','main_block_styles'],
    data(){
        return{     	
        	H: new Helper(),
        	ajaxMarker: false,
	        categories: [],
	        editorColorsList: false,
        	editData: false,
        	editorData: '',
        	editorConfig: {
	            height: 250
	        },
        	currentEvent:{
				term_id: 0,
				category_id: 0
			},
	        disabled: false,
			deleting: false,
	        eventsList: [],
			flash:{
				type: false,
				title: false,
				text: false,
				status: false
			},
	        sortedKey: '',
	        terms: []
        }
    },
    methods:{
    	dataToEditor(event){
    	 	this.editData = true
    	 	this.currentEvent = JSON.parse( JSON.stringify( event ) )
    	 	this.editorData = this.currentEvent.content = event.content
    	 	this.$refs['select_term'].value = this.currentEvent.term_id
			this.$refs['select_category'].value = this.currentEvent.category_id
    	 	this.$refs['editor_section'].scrollIntoView({block: "center", behavior: "smooth"})
    	 },
		changeCategory(target){
			this.currentEvent.category_id = Number(target.value)
			this.currentEvent.category_name = target.selectedOptions[0].text
		},
		changeTerm(target){
			this.currentEvent.term_id = Number(target.value)
			this.currentEvent.term_name = target.selectedOptions[0].text
		},
    	getEventsList(){
			NProgress.start()
           ajax               
            .get('/events')              
            .then(data =>  {
                    try{
						// console.log('data: ',data) 
						const parsed = JSON.parse(data)
						//console.log('parsed: ',parsed) 
                        this.eventsList = parsed
						this.orderEventsList()
						NProgress.done()
                    }catch(e){
                        this.eventsList = []
                        this.terms = []
                        this.categories = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE')
                    }
                    
                },             
                err => {
					console.log('err: ',err) 
					alert('ERROR: SEE CONSOLE') 
                }           
              )    	 	
    	 },
		getTerms(){
           ajax               
            .get('/terms')              
            .then(data =>  {
                    try{
						// console.log('data: ',data) 
						const parsed = JSON.parse(data)
						//console.log('parsed: ',parsed) 
                        this.terms = parsed
                        this.orderEventsList()
                    }catch(e){
                        this.terms = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE')
                    }
                    
                },             
                err => {
					console.log('err: ',err)  
					alert('ERROR: SEE CONSOLE')
                }           
              )    	 	
    	 },
		getCategories(){
           ajax               
            .get('/categories')              
            .then(data =>  {
                    try{
						//console.log('data: ',data) 
						const parsed = JSON.parse(data)
						//console.log('parsed: ',parsed) 
                        this.categories = parsed
                        this.orderEventsList()
                    }catch(e){
                        this.categories = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE')
                    }
                    
                },             
                err => {
					console.log('err: ',err)  
					alert('ERROR: SEE CONSOLE')
                }           
              )    	 	
    	 },
        editEvent(currentEvent,paramsToChange){      	
        	this.disabled = true
        	this.ajaxMarker = true
        	const objectNormalize = JSON.parse(JSON.stringify(currentEvent))
        	Object.keys(paramsToChange).forEach( key => { objectNormalize[key] = paramsToChange[key] })
    	 	ajax.put('/event/update',objectNormalize,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
	            				// console.log('parsed: ',parsed)
					            this.eventsList.forEach((event,i)=>{
					                if(event.id === currentEvent.id){
					                	this.eventsList[i] = objectNormalize
					                }               
					            })  
	            				this.orderEventsList()
			                    this.editorData = ''
			                    this.currentEvent = {}
								this.$refs['select_term'].value = 0
								this.$refs['select_category'].value = 0
			                    this.flash = { type: "success", title: "done", status: true }
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	            		}finally{
	            			this.disabled = false
	            			this.editData = false	
	            			this.ajaxMarker = false
	            		}
	            		
	                },             
	                err => {
	                	this.disabled = false
	                	this.ajaxMarker = false
	                    console.log('err: ',err) 
						this.flash = { type: "danger", title: "error", text: 'see console for details', status: true }  
	                }           
	              )  
        },
    	 addEvent(){
    	 	this.disabled = true
    	 	this.ajaxMarker = true
			this.currentEvent.content = this.editorData
    	 	ajax.post('/event/add',this.currentEvent,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			// console.log('parsed: ',parsed)
	            			if(parsed.status){
								this.currentEvent.id = parsed.id
								this.currentEvent.term_name = this.terms.find(term => this.currentEvent.term_id === term.id).name
								this.currentEvent.category_name = this.categories.find(category => this.currentEvent.category_id === category.id).name
	            				this.eventsList.push(this.currentEvent)
	            				this.orderEventsList()
			                    this.editorData = ''
			                    this.currentEvent = {}  
								this.$refs['select_term'].value = 0
								this.$refs['select_category'].value = 0 
								this.flash = { type: "success", title: "done", status: true }      				
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	            		}finally{
	            			this.disabled = false
	            			this.editData = false
	            			this.ajaxMarker = false	 
	            		}
	                },             
	                err => {
	                	this.disabled = false
	                	this.ajaxMarker = false
	                    console.log('err: ',err)
						this.flash = { type: "danger", title: "error", text: 'see console for details', status: true }  
	                }           
	              )     	 	
    	 },
    	 deleteEvent(id){
    	 	this.disabled = true
			this.deleting = true
    	 	ajax.delete('/event/delete',id,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
					            const index = this.eventsList.findIndex( event => event.id === id )   
                                this.eventsList.splice(index,1) 
	            				
	            			}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: SEE CONSOLE')
	            		}finally{
	            			this.disabled = false 
							this.deleting = false
	            		}	            		
	                },             
	                err => {
	                	this.disabled = false
						this.deleting = false
						console.log('err: ',err)
						alert('ERROR: SEE CONSOLE')   
	                }           
	              )     	 	
    	 },
         orderEventsList(){
            const pending = this.eventsList.filter(event => !event.status )
            const done = this.eventsList.filter(event => event.status )
            this.eventsList = pending.concat(done)
        },
        sortBy(key){
        	if(this.sortedKey === key){
        		this.sortedKey = ''
        		key = 'id'
        	}else{
        		this.sortedKey = key
        	}       	
            const pending = this.eventsList.filter(event => !event.status )
            pending.sort((a,b) => a[key] - b[key])
            const done = this.eventsList.filter(event => event.status )
            this.eventsList = pending.concat(done)     	
        },
        removeData(){
        	this.currentEvent = {}
        	this.currentEvent.term_id = 0
			this.currentEvent.category_id = 0
        	this.editorData = ''
        	this.editData = false
        },
		hideFlash(){
			Object.keys(this.flash).forEach(key => this.flash[key] = false)
		}
    },
    computed:{
    	validation(){
    		  const r = [this.editorData,
	    			this.currentEvent.category_id,
	    			this.currentEvent.term_id,
		    		].filter(item => item)
		    	return r.length === 3
        }	
    },
    mounted(){
    	this.getEventsList()		
    	this.getTerms()
		this.getCategories()

    	this.H.gridMasonry()
    }
};
</script>
