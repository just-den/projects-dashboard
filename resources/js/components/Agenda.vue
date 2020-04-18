<template>
	<main
		:style="main_block_styles">
		<div class="title">
		    <h4>   	
		   		today  	
		    </h4>
		    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
		    	<path d="M33.06 22.12L30.94 20l-9.76 9.76-4.24-4.24-2.12 2.12L21.18 34l11.88-11.88zM38 6h-2V2h-4v4H16V2h-4v4h-2c-2.21 0-3.98 1.79-3.98 4L6 38c0 2.21 1.79 4 4 4h28c2.21 0 4-1.79 4-4V10c0-2.21-1.79-4-4-4zm0 32H10V16h28v22z"/>
		    </svg>
	    </div>
	   <section class="agenda">	
		   	<ul is="transition-group" name="animation-list">
				<li 
					class="item"
					v-for="todo, index in agendaList" 
					:class="{ 'in_work': todo.status === 'in-work', 'done': todo.status === 'done', 'pending': todo.status === 'pending' }"
	   				:key="todo.id"	
		   		>
					<a href="javascript:void(0)" class="pin">
		   				<label 
		   					:class="{ 'disabled': disabled }" 
		   				>
		   					<input 
		   						type="checkbox" 
		   						:checked="todo.status === 'in-work'"
		   						@change="editTodo($event.target,todo,'hurry-not-hurry')"
		   					>
		   					<svg 
								v-if="todo.status === 'in-work'"
								xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<circle cx="256" cy="96" r="64" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M272 164a9 9 0 00-9-9h-14a9 9 0 00-9 9v293.56a32.09 32.09 0 002.49 12.38l10.07 24a3.92 3.92 0 006.88 0l10.07-24a32.09 32.09 0 002.49-12.38z"/><circle cx="280" cy="72" r="24"/>
							</svg>
							<svg 
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512"
								v-else-if="todo.status === 'pending' || 'done'"
							>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M112 244l144-144 144 144M256 120v292"/>
							</svg>
		   				</label> 
					</a>
					<div class="num">
						<span v-text="index + 1"></span>
						<span
		   					v-text="todo.status_label"
						></span>
					</div>
					<div 
						class="body"
						v-html="todo.content"
					>
					</div>
					<div class="status">
						<label
							:class="{'disabled': disabled}">
							<input 
								type="checkbox"
		   						:checked="todo.status === 'done'"
		   						:key="todo.id"
		   						@change="editTodo($event.target,todo,'done-not-done')"
				   			>
				   			<svg 
				   				xmlns="http://www.w3.org/2000/svg" 
				   				viewBox="0 0 512 512"
				   				v-if="todo.status === 'done'"
				   			>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M464 128L240 384l-96-96M144 384l-96-96M368 128L232 284"/>
							</svg>
							<svg 
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512"
								v-else-if="todo.status === 'pending'"
							>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/>
							</svg>
						</label>
					</div>
					<nav>
						<a 
							@click.prevent="dataToEditor(todo)"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M364.13 125.25L87 403l-23 45 44.99-23 277.76-277.13-22.62-22.62zM420.69 68.69l-22.62 22.62 22.62 22.63 22.62-22.63a16 16 0 000-22.62h0a16 16 0 00-22.62 0z"/>
							</svg>
						</a>
						<a 
		   					:class="{'disabled': disabled}"
		   					@click.prevent="deleteTodo(todo.id)"
						>
							<svg 
								v-if="!deleting"
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512">
								<path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
								<path stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/>
								<path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
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
					</nav>
				</li>
			</ul>			
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
				<ckeditor 
					v-model="editorData" 
					:config="editorConfig"
				></ckeditor>
				<div v-if="editorData">
					<transition	mode="out-in">
						<button
							:class="{'disabled': disabled || !editorData}"
							@click.prevent="addTodo()"
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
							@click.prevent="editTodo($event.target,'','')"
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
			agendaList: '',
			ajaxMarker: false,
        	currentTodo:{},
        	disabled: false, 
			deleting: false,      	
        	editData: false,
        	editorData: '',
        	editorConfig: {
	            height: 250
	        },
			flash:{
				type: false,
				title: false,
				text: false,
				status: false
			}
        }
    },
    methods:{
    	 dataToEditor(todo){
    	 	this.editData = true
    	 	this.currentTodo = todo
    	 	this.editorData = this.currentTodo.content = todo.content
    	 	this.$refs['editor_section'].scrollIntoView({block: "center", behavior: "smooth"})
    	 },
    	 getTodoList(){
		   NProgress.start()
           ajax               
            .get('/todo')              
            .then(data =>  {
                    try{
                    	// console.log('data: ',data)
                    	const parsed = JSON.parse(data)
                    	// console.log('data: ',parsed)
                    	if(parsed.length != 0){	                        
	                        this.agendaList = parsed
	                        this.orderAgendaList()	                        
	                    }else{
	                    	this.agendaList = []
						}
						NProgress.done()
                    }catch(e){
                        this.agendaList = []
                        console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE') 
                    }finally{
	            			this.disabled = false
	            			this.editData = false	 
	            		}
                    
                },             
                err => {					
					console.log('err: ',err) 
					alert('ERROR: SEE CONSOLE')  
                }           
              )    	 	
    	 },
    	 addTodo(){
			 this.disabled = true
			 this.ajaxMarker = true
            const params = {
                status: 'pending',
                status_label: 'ожидает',
                content: this.editorData
            }
    	 	ajax.post('/todo/add',params,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
								this.flash = { type: "success", title: "done", status: true }
	            				params.id = parsed.id
	            				this.agendaList.push(params)
	            				this.orderAgendaList()
			                    this.disabled = false
			                    this.editorData = ''
			                    this.currentTodo = {}            				
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
	                    console.log('err: ',err)  
						this.disabled = false 
						this.ajaxMarker = false
						this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	                }           
	              )     	 	
    	 },
    	 editTodo(e,todo,marker){
			 this.disabled = true
			 this.ajaxMarker = true
            const params = {
                id: this.currentTodo.id || todo.id,               
                content: this.editorData || this.currentTodo.content || todo.content 
            }
            switch(marker){
            	case 'done-not-done':{
            		params.status = e.checked ? 'done' : 'pending'
            		params.status_label = e.checked ? 'done' : 'pending'
            		break
            	}
				case 'hurry-not-hurry':{
            		params.status = e.checked ? 'in-work' : 'pending'
            		params.status_label = e.checked ? 'in work' : 'pending'
            		break
            	}
            	default:{
             		params.status = this.currentTodo.status || todo.status
            		params.status_label = this.currentTodo.status_label || todo.status_label         		
            	}
            }           	
    	 	ajax.put('/todo/update',params,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
								this.flash = { type: "success", title: "done", status: true }
					            this.agendaList.forEach((todo)=>{
					                if(todo.id === params.id){
					                    todo.status = params.status  
					                    todo.status_label = params.status_label
					                    todo.content = params.content
					                }               
					            })  
	            				this.orderAgendaList()
			                    this.editorData = ''
			                    this.currentTodo = {}
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
    	 deleteTodo(id){
    	 	this.disabled = true
			this.deleting = true
    	 	ajax.delete('/todo/delete',id,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
					            const index = this.agendaList.findIndex( todo => todo.id === id )   
                                this.agendaList.splice(index,1) 
	            				this.orderAgendaList()
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
         orderAgendaList(){
            const inWork = this.agendaList.filter(todo => todo.status === 'in-work')
            const pending = this.agendaList.filter(todo => todo.status === 'pending')
            const done = this.agendaList.filter(todo => todo.status === 'done')
            this.agendaList = inWork.concat(pending,done)
        },
        removeData(){
        	this.currentTodo = {}
        	this.editorData = ''
        	this.editData = false
        },
		hideFlash(){
			Object.keys(this.flash).forEach(key => this.flash[key] = false)
		}
    },
    mounted(){
    	this.getTodoList()		
    }
};
</script>


