<template>
	<main
		:style="main_block_styles"
		>
        <div class="title">
        <h4>   	
                settings
        </h4>
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 128h80M64 128h240M368 384h80M64 384h240M208 256h240M64 256h80"/>
			<circle cx="336" cy="128" r="32" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
			<circle cx="176" cy="256" r="32" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
			<circle cx="336" cy="384" r="32" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
		</svg>
        </div>	
        <section class="settings">
			<h4>categories</h4>
            <div class="cats_tags_block">
                <div 
                    v-for="category, i in categories"
                    class="category_label"
				>
                    <div v-text="category.name"></div>
                    <div 
                        v-for="tag in category.tags"
                        :style="{backgroundColor: tag.color.name}"                       
                    >
						<span 
							:class="{ 'disabled': tag.disabled }"
							class="del"
							@click.prevent = deleteTag(category.id,tag.id)
						>x</span>
                        <span 
							v-text="tag.name"	
							class="name"								
						></span>
					</div>
					<div class="add_tag_block">
						<div 
							class="add_tag_block_in"
							v-if="category.tagNew.show"
						>
							<input 
								type="text"	
								:value="category.tagNew.name"						
								@input="setTagNewNameFromCat($event.target.value,i)"
							>
							<input 
								type="color"
								v-model="category.tagNew.color"
							>
						</div>
						<span 
							v-if="!category.tagNew.show"
							class="add_tag"
							@click.prevent="showTagForm(category.id)"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"/>
							</svg>
						</span>
						<span 
							v-if="category.tagNew.show"
							class="add_tag"
							@click.prevent="resetTag(category.id)"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/>
							</svg>
						</span>
						<span 
							v-if="category.tagNew.show"
							class="add_tag"
							:class="{ 'disabled': category.disabled }"
							@click.prevent="addTag(category.id,i)"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/>
							</svg>
						</span>
					</div>
					<a 
						href="#"
						:class="{ 'disabled': category.disabled }"
						@click.prevent="deleteCategory(category.id,i)"
					>
						<svg 
							v-if="!category.preloader"
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
					<a 
						href="#"
						@click.prevent="dataToEditor(category)"
					>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M364.13 125.25L87 403l-23 45 44.99-23 277.76-277.13-22.62-22.62zM420.69 68.69l-22.62 22.62 22.62 22.63 22.62-22.63a16 16 0 000-22.62h0a16 16 0 00-22.62 0z"/>
						</svg>
					</a>
				</div>				
				<form 
					ref="editor_section"
				>
					<div class="message">
						<transition>
							<Flash 
								v-if="flash.status"
								:title="flash.title"
								:text="flash.text"
								:flash_type="flash.type"	
								@hide_flash="hideFlash"					
							></Flash>
						</transition>
					</div>
					<h5>add new</h5>
					<transition-group>
						<div 
							class="fieldset"
							v-for="categoryNew, i in categoriesNew"
							:key="'c' + i"
						>
							<div class="fftgb">
								<input 
									type="text" 
									placeholder="Category"
									v-model="categoryNew.name"
									:class="{'validation_false': !categoryNew.validation}"
								>
							</div>
							<transition-group>
								<div 
									class="fftgb"
									v-for="tagNew, j in categoryNew.tags"
									:key="'tc' + i + j">
									<input 
										type="text" 
										placeholder="Тег"
										:value="tagNew.name"
										@input="setTagNewName($event.target.value, i, j)"
										:class="{'validation_false': !tagNew.validation}"					
									>
									<input 
										type="color"
										v-model="tagNew.color"
										>
									<a 
										href="#"
										v-if="tagNew.sign"
										@click.prevent = "addNewTag(i,j,tagNew.sign)"
										class="add_tag"
										v-text="tagNew.sign"
									></a>
								</div>						
							</transition-group>						
							<div class="fftgb">
								<button
									v-if="categoryNew.sign"
									@click.prevent="addNewCategory(i,categoryNew.sign)"
								>
									<span v-text="categoryNew.sign"></span>
									<span>category</span> 
								</button>
							</div>
						</div>
					</transition-group>
					<div class="buttons">
						<button 
							@click.prevent="addCategory()"
							class="add"
							v-if="button.status === 'add'"
						>
							<svg 
								v-if="button.preloader"
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512"
								class="animation-rotation">
								<path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
							</svg>
							<span>add</span>
						</button>
						<button
							v-if="button.status === 'edit'"
							@click.prevent="editCategory()"
							class="edit"
						>
							<svg 
								v-if="button.preloader"
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512"
								class="animation-rotation">
								<path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
							</svg>
							<span>edit</span>
						</button>	
						<button
							v-if="button.status === 'edit'"
							@click.prevent="resetData()"
							class="remove"
						>
							<span>reset</span>
						</button>
					</div>			
				</form>
            </div> 			
		</section>
		<section class="settings">
			<h4>terms</h4>
			<div class="add_term">
				<input 
					type="text" 
					placeholder="add new"
					v-model="formTermsAdd.name"
				>
				<transition>
					<a 
						href="#"
						@click.prevent="addNewTerm()"
						:class="{'disabled': formTermsAdd.disabled}"
						v-if="formTermsAdd.name"
					>
						<svg 
							xmlns="http://www.w3.org/2000/svg" 
							viewBox="0 0 512 512"
							v-if="!formTermsAdd.preloader"
						>
							<path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/>
						</svg>
						<svg 
							v-else
							xmlns="http://www.w3.org/2000/svg" 
							viewBox="0 0 512 512"
							class="animation-rotation">
							<path fill="none" stroke="#fff" d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
							<path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
						</svg>
					</a>
				</transition>
			</div>
			<div 
				class="terms_list"
				:class="{ disabled: formTermsAdd.disabled }"
			>
				<draggable 
					v-model="terms"
					@update="dragTermsUpdate()"
				>
					<transition-group name="animation-list" tag="ul">
						<li 
							v-for="term in terms" 
							:key="term.id"							
						>
							<span 
								v-if="!term.edit"
								v-text="term.name">
							</span>
							<input 
								type="text"
								v-else
								v-model="formTermsEdit.name"
							>
							<nav>
								<a 
									href="#"
									v-if="!term.edit"
									@click.prevent="term.edit = true, formTermsEdit.name = term.name"
								>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M364.13 125.25L87 403l-23 45 44.99-23 277.76-277.13-22.62-22.62zM420.69 68.69l-22.62 22.62 22.62 22.63 22.62-22.63a16 16 0 000-22.62h0a16 16 0 00-22.62 0z"/>
									</svg>
								</a>
								<a 
									href="#"
									:class="{'disabled': term.disabled}"
									@click.prevent="editTerm(term,formTermsEdit.name)"
									v-if="term.edit && formTermsEdit.name"
								>
									<svg 
										xmlns="http://www.w3.org/2000/svg" 
										viewBox="0 0 512 512"
										v-if="!term.preloader"
									>
										<path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96"/>
									</svg>
									<svg 
										xmlns="http://www.w3.org/2000/svg" 
										viewBox="0 0 512 512"
										v-else
										class="animation-rotation"
									>
										<path fill="none" stroke="#000" d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
										<path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
									</svg>
								</a>
								<a 
									href="#"
									:class="{'disabled': term.disabled}"
									@click.prevent="deleteTerm(term)"
									v-if="!term.edit"
								>
									<svg 
										xmlns="http://www.w3.org/2000/svg" 
										viewBox="0 0 512 512"
										v-if="!term.preloader"
									>
										<path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path stroke="#000" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/><path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
									</svg>
									<svg 
										v-else
										xmlns="http://www.w3.org/2000/svg" 
										viewBox="0 0 512 512"
										class="animation-rotation">
										<path fill="none" stroke="#000" d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
										<path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
									</svg>
								</a>
								<a 
									href="#"
									v-if="term.edit"
									@click.prevent="term.edit = false, formTermsEdit.name = ''"
								>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path d="M448 256c0-106-86-192-192-192S64 150 64 256s86 192 192 192 192-86 192-192z" fill="none" stroke="#000" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M320 320L192 192M192 320l128-128"/>
									</svg>
								</a>
							</nav>
						</li>
					</transition-group>
				</draggable>
			</div>
		</section>
    </main>	
</template>

<script>
import draggable from 'vuedraggable'
import { Helper } from '@/js/utilities/helper'

export default {
	props: ['csrf','main_block_styles'],
	components: {
            draggable
    },
    data(){
        return{     	
			H: new Helper(),
			button: {
				preloader: false,
				status: 'add'
			},	
			categories: [],
			categoriesNew: [
				{
					name: '',
					sign: '+',
					validation: true,
					tags:[
						{
							name: '',
							color: '#d2d2d2',
							sign: '+',
							validation: true
						}
					]
				}
			],
	        disabled: false,
			flash:{
				type: '',
				title: '',
				text: '',
				status: ''
			},			
			tags: [],
			terms: [],
			formTermsAdd:{
				disabled: false,			
				name: '',
				preloader: false
			},
			formTermsEdit:{		
				name: ''
			}
        }
    },
    methods:{
		dataToEditor(cat){			
			const _cat = JSON.parse( JSON.stringify( cat ) )
			this.categoriesNew[0].id = _cat.id
			this.categoriesNew[0].name = _cat.name
			this.categoriesNew[0].sign = ''
			if(_cat.tags && _cat.tags.length !== 0 ){
				this.categoriesNew[0].tags = _cat.tags.map(tag=>{
					tag.validation = true
					tag.color = tag.color.name
					tag.sign = '-'
					return tag
				})
			}
			this.button.status = 'edit'
			this.$refs['editor_section'].scrollIntoView({block: "center", behavior: "smooth"})
		},
        resetData(){
			this.categoriesNew = [{name: '',sign: '+',validation: true,tags:[{name: '',color: '#d2d2d2',sign: '+',validation: true}]}]
			this.button.status = 'add'
        },
		hideFlash(){
			Object.keys(this.flash).forEach(key => this.flash[key] = '')
		},
		addNewTag(i,j,sign){
			switch(sign){
				case '+':{
					this.categoriesNew[i].tags.push({name: '',color:'#d2d2d2',sign:'-', validation: true})
					break
				}
				case '-':{
					const index = this.categoriesNew[i].tags.findIndex( ( item, _index ) => _index === j )
					if(index !== -1){
						this.categoriesNew[i].tags.splice(index,1)	
					}
					break
				}
				default:{}
			}			
		},
		addNewCategory(i,sign){
			switch(sign){
				case '+':{
					this.categoriesNew.push( { name: '', sign: '-', validation: true, tags: [ {name: '',color:'#d2d2d2',sign:'+', validation: true} ] } )
					break
				}
				case '-':{
					const index = this.categoriesNew.findIndex( ( item, _index ) => _index === i )
				if(index !== -1){
					this.categoriesNew.splice(index,1)	
				}	
					break
				}
				default:{}
			}	
		},
		validation(){

			let flagEmpty = 0
			let flagUnique = 0
			let message = `<ul>`

			this.categoriesNew.forEach(cat => {
				if(	!/([^\s])/.test(cat.name) ){
					flagEmpty++
					cat.validation = false
				}else{
					cat.validation = true
				}
				cat.tags.forEach(tag => {
					if(	!/([^\s])/.test(tag.name) ){
						flagEmpty++
						tag.validation = false
					}else{
						tag.validation = true
					}
				})	
			})

			if(flagEmpty > 0){
				message += `<li>empty fields</li>`
			}

			if(this.button.status === 'add'){
				if(/([^\s])/.test(this.categoriesNew[0].name)){
					this.categoriesNew.forEach(cat => {
						if( this.categories.find(_cat => _cat.name === cat.name ) ){
							flagUnique++
							cat.validation = false
						}else{
							cat.validation = true
						}
					})
				}
			}

			if(flagUnique > 0){
				message += `<li>category name is taken</li>`
			}

			message += `</ul>`

			if(flagEmpty > 0 || flagUnique > 0){
				this.flash = { type: "danger", title: "see errors", text: message, status: true } 
				return
			}

			// this.hideFlash()
			return true

		},
		addFieldsToCats(cats){
			const _new = cats.map(cat => { 
					cat.preloader = false
					cat.disabled = false 
					cat.tagNew = { show: false, name: '',color: '#d2d2d2' }	
					if(cat.tags.length !== 0){
						cat.tags.forEach(tag => {
							tag.disabled = false 
						})
					}
					return cat
				})
			return _new
		},
		showTagForm(catId){
			this.categories.forEach(cat => {
				cat.tagNew = { show: false, name: '',color: '#d2d2d2' }	
				if(cat.id === catId){
					cat.tagNew.show = true
				}
			})			
		},
		resetTag(catId){
			let cat = this.categories.find(cat => cat.id === catId)
			cat.tagNew = { show: false, name: '',color: '#d2d2d2' }	
		},
		getCategories(){
		   NProgress.start()		   
           ajax               
            .get('/categories')              
            .then(data =>  {
                    try{
						// console.log('data: ',data) 
						this.categories = this.addFieldsToCats(JSON.parse(data))
						NProgress.done()
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
		 addCategory(){
			if(!this.validation()){ return }
			this.disabled = true
			this.button.preloader = true
    	 	ajax.post('/category/add',this.categoriesNew,this.csrf,true)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			// console.log('addCategory parsed: ',parsed)
	            			if(parsed.status){
								const cats = this.addFieldsToCats(parsed.body)
								this.categories = this.categories.concat( cats )
								this.resetData()
								this.flash = { type: "success", title: "done", status: true }
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	            		}finally{
	            			this.disabled = false
	            			this.button.preloader = false	 
	            		}
	                },             
	                err => {
	                	this.disabled = false
	                	this.button.preloader = false
	                    console.log('err: ',err)
						this.flash = { type: "danger", title: "error", text: 'see console for details', status: true }  
	                }           
	              )  			 
		 },
		 editCategory(){  
			if(!this.validation()){ return }    	
        	this.disabled = true
        	this.button.preloader = true
			const catToUpdate = JSON.parse( JSON.stringify( this.categoriesNew[0] ) )
    	 	ajax.put('/category/update',catToUpdate,this.csrf,true)              
	            .then(data =>  {
	            		console.log('data: ',data)
	            		try{
							const parsed = JSON.parse(data)
							console.log('parsed: ',parsed)
	            			if(parsed.status === true){	
					            this.categories.forEach((cat)=>{
					                if(cat.id === catToUpdate.id){
										cat.name = catToUpdate.name
										cat.tags.forEach((tag,i)=>{
											const _tag = catToUpdate.tags.find(t => t.id === tag.id)
											if(_tag){
												tag.name = _tag.name
												tag.color.name = _tag.color
											}
										})
					                }               
					            })  
								this.resetData()
			                    this.flash = { type: "success", title: "done", status: true }
	            			}else if(parsed.status === false){
								this.flash = { type: "danger", title: "error", text: parsed.message, status: true }
							}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	            		}finally{
	            			this.disabled = false
	            			this.button.status = 'add'	
	            			this.button.preloader = false	 
	            		}
	            		
	                },             
	                err => {
	                	this.disabled = false
	                	this.button.preloader = false	 
	                    console.log('err: ',err) 
						this.flash = { type: "danger", title: "error", text: 'see console for details', status: true }  
	                }           
	              )  
		},
		 deleteCategory(id,i){
			this.categories[i].disabled = true
			this.categories[i].preloader = true
    	 	ajax.delete('/category/delete',id,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
					            const index = this.categories.findIndex( cat => cat.id === id )   
								this.categories.splice(index,1) 
								this.resetData()	            				
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
	            		}finally{
	            			this.disabled = false 
							if(this.categories[i]){
								this.categories[i].disabled = false
								this.categories[i].preloader = false
							}
	            		}
	            		
	                },             
	                err => {
	                	this.disabled = false
						if(this.categories[i]){
								this.categories[i].disabled = false
								this.categories[i].preloader = false
							}
	                    console.log('err: ',err)   
	                }           
	              )   			 
		 },
		 addTag(catId,i){

			 if( !/([^\s])/.test(this.categories[i].tagNew.name) ){
				alert('Fill name field')
				return
			 }

			const tags = this.categories[i].tags
			 for(let j in tags){
				if(tags[j].name === this.categories[i].tagNew.name){
					alert('Tag\'s name is taken')
					return
				}
			 } 

			this.categories[i].disabled = true

			const tag = { 
					category_id: catId, 
					name: this.categories[i].tagNew.name, 
					color: this.categories[i].tagNew.color 
				}

			ajax.post('/tag/add',tag,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			// console.log('addCategory parsed: ',parsed)
	            			if(parsed.status === true){
								const newTag = {
										color:{
											id: parsed.color_id,
											name: tag.color
										},
										id: parsed.tag_id,
										name: tag.name,
										disabled: false
									}
								this.categories[i].tags.push(newTag)
								this.resetTag(catId)
	            			}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: see console for details')
	            		}finally{
	            			this.categories[i].disabled = false 
	            		}
	                },             
	                err => {
	                	this.categories[i].disabled = false
	                    console.log('err: ',err)
						alert('ERROR: see console for details') 
	                }           
	              ) 

		 },
		 deleteTag(catId,tagId){
			this.disabled = true
    	 	ajax.delete('/tag/delete',tagId,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status === true){
								const cat = this.categories.find(cat => cat.id === catId)
								console.log('cat: ',cat)
								const index = cat.tags.findIndex( tag => tag.id === tagId )   
								cat.tags.splice(index,1) 	            				
	            			}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: see console for details')
	            		}finally{
	            			this.disabled = false 
	            		}
	            		
	                },             
	                err => {
	                	this.disabled = false
						if(this.categories[i]){
								this.categories[i].preloader = false
							}
						console.log('err: ',err)  
						alert('ERROR: see console for details') 
	                }           
	              )   			 
		 },
		 setTagNewName(v,i,j){
			this.categoriesNew[i].tags[j].name = v
			ajax               
            .get('/tag/get-recommended-color',{name: v})              
            .then(data =>  {
                    try{
						// console.log('data: ',data) 
						const parsed = JSON.parse(data)
						if(parsed.status === true){
							// console.log('NAME: ',parsed.name) 
							this.categoriesNew[i].tags[j].color = parsed.name
						}else{
							this.categoriesNew[i].tags[j].color = '#d2d2d2'
						}						
                    }catch(e){
                        alert('ERROR: see console for details')
                        console.log('PARSING DATA ERROR: ',e.message)
                    }                   
                },             
                err => {
					console.log('err: ',err)  
					alert('ERROR: see console for details')
                }           
              )
		 },
		 setTagNewNameFromCat(v,i){
			this.categories[i].tagNew.name = v
			ajax               
            .get('/tag/get-recommended-color',{name: v})              
            .then(data =>  {
                    try{
						// console.log('data: ',data) 
						const parsed = JSON.parse(data)
						// console.log('parsed: ',parsed) 
						if(parsed.status === true){							
							this.categories[i].tagNew.color = parsed.name
						}else{
							this.categories[i].tagNew.color = '#d2d2d2'
						}						
                    }catch(e){
                        alert('ERROR: see console for details')
                        console.log('PARSING DATA ERROR: ',e.message)
                    }                   
                },             
                err => {
					console.log('err: ',err)
					alert('ERROR: see console for details')  
                }           
              )
		 },
		 getTerms(){		   
           ajax               
            .get('/terms')              
            .then(data =>  {
                    try{
						// console.log('data: ',data) 
						this.terms = JSON.parse(data).map(item => {
							item.preloader = false
							item.disabled = false
							item.edit = false
							return item
						})
                    }catch(e){
                        this.terms = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: see console for details')
                    }                   
                },             
                err => {
					console.log('err: ',err) 
					alert('ERROR: see console for details') 
                }           
              )    	 	
		 },
		 addNewTerm(){
			 this.formTermsAdd.disabled = true
			 this.formTermsAdd.preloader = true
			const data = { name: this.formTermsAdd.name }
    	 	ajax.post('/term/add',data,this.csrf)              
	            .then(data =>  {
	            	    // console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			// console.log('parsed: ',parsed)
	            			if(parsed.status){
								const term = {
									disabled:false,
									edit: false,
									id: parsed.id,
									name: this.formTermsAdd.name,
									order:0,
									preloader:false,
								}
								this.terms.unshift(term) 
								this.formTermsAdd.name = ''				
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: see console for details')
	            		}finally{
							this.formTermsAdd.disabled = false
			 				this.formTermsAdd.preloader = false
	            		}
	                },             
	                err => {
						this.formTermsAdd.disabled = false
			 			this.formTermsAdd.preloader = false
						console.log('err: ',err) 
						alert('ERROR: see console for details')
	                }           
	              )     	 	
		 },
		 termsNewOrder(){
			this.terms = this.terms.map((item,i) => {
				item.order = i + 1
				return item
			 })
		 },
		 termEditOn(){

		 },
		 dragTermsUpdate(){
			this.formTermsAdd.disabled = true		
			this.termsNewOrder()					
			ajax.put('/term/update-order',this.terms,this.csrf,true)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			// console.log('parsed: ',parsed)
	            			if(parsed.status === true){
											
	            			}else if(parsed.status === false){
								console.log('VALIDATION ERROR: ',parsed.message)
							}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: see console for details')
	            		}finally{
							this.formTermsAdd.disabled = false
	            		}
	                },             
	                err => {
						this.formTermsAdd.disabled = false
						console.log('err: ',err) 
						alert('ERROR: see console for details')
	                }           
				  )   
					
		 },
		 deleteTerm(term){
			term.disabled = true
			term.preloader = true
			const ID = term.id
    	 	ajax.delete('/term/delete',ID,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
					            const index = this.terms.findIndex( term => term.id === ID )   
                                this.terms.splice(index,1) 	            				
	            			}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: see console for details')
	            		}finally{
							term.disabled = false
							term.preloader = false
	            		}	            		
	                },             
	                err => {
						term.disabled = false
						term.preloader = false
						console.log('err: ',err) 
						alert('ERROR: see console for details')  
	                }           
	              ) 
		 },
		 editTerm(term,name){
			term.disabled = true
			term.preloader = true
			const ID = term.id
			ajax.put('/term/update',{name, id: ID},this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
	            				console.log('parsed: ',parsed)
					            this.terms.forEach((term,i)=>{
					                if(term.id === ID){
					                	this.terms[i].name = name
					                }               
					            })  
	            			}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: see console for details')
	            		}finally{
							term.disabled = false
							term.preloader = false
							term.edit = false
							this.formTermsEdit.name = ''
	            		}	            		
	                },             
	                err => {
						term.disabled = false
						term.preloader = false
						term.edit = false
						this.formTermsEdit.name = ''
						console.log('err: ',err) 
						alert('ERROR: see console for details')
	                }           
	              )  
		 }
    },
    mounted(){	
		this.getCategories()
		this.getTerms()			
    }
};
</script>
