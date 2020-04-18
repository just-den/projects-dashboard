<template>
	<main
		:style="main_block_styles"
		@click="closeMultiselectList($event.target)"
		>
		 <div class="title">
			<h4>   	
				   links
			</h4>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
				<path d="M34 6H14c-2.21 0-3.98 1.79-3.98 4L10 42l14-6 14 6V10c0-2.21-1.79-4-4-4zm0 30l-10-4.35L14 36V10h20v26z"/>
			</svg>
		 </div>	
		<section class="search">
			<form>
				<div class="form_block">
					<transition>
						<svg 
							v-if="!searchData"
							xmlns="http://www.w3.org/2000/svg" 
							viewBox="0 0 48 48" 
							class="search"
							key="search">
							<path d="M31 28h-1.59l-.55-.55C30.82 25.18 32 22.23 32 19c0-7.18-5.82-13-13-13S6 11.82 6 19s5.82 13 13 13c3.23 0 6.18-1.18 8.45-3.13l.55.55V31l10 9.98L40.98 38 31 28zm-12 0c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"/>
						</svg>
						<svg 
							v-else
							xmlns="http://www.w3.org/2000/svg" 
							viewBox="0 0 512 512"
							key="close"
							class="search"
							@click.prevent="resetSearchData">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/>
						</svg>					
					</transition>
					<input 
						type="text" 
						class="search" 
						placeholder="find link"
						v-model="searchData"
					>
					<transition>
						<a 
							href="#" 
							class="search"
							:class="{'disabled': disabled}"
							v-if="searchData"
							@click.prevent="getLinksLike()"
						>
							<svg 
								v-if="buttonPreloader"
								xmlns="http://www.w3.org/2000/svg" 
								viewBox="0 0 512 512"
								class="animation-rotation spin">
								<path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
							</svg>
							<span>find</span> 
						</a>					
					</transition>
				</div> 
				<div class="form_block">
					<span>show: </span>
					<label 
						:class="{active: showCategoriesWithTags}"
					>
						<span
							v-show="showCategoriesWithTags"							
						>x</span>
						<span>categories and tags</span>
						<input 
							type="checkbox"
							@change="toggleCategoriesWithTags($event.target.checked)"
						>
					</label>					
				</div>
				<div class="cats_tags_block">
					<transition>
						<div v-if="showCategoriesWithTags">
							<div 
								v-for="category in categoriesToDisplay"
								:key="category.name + category.id"
								class="category_label"
							>
								<label 
									:class="{ 'disabled': disabled, 'opacity': activeCatTag !== 0 && !category.active }"
								>
									<span v-show="category.active">x</span>
									<span v-text="category.name"></span>
									<input 
										type="checkbox"
										:checked="category.active"
										@change="getLinksByCategory($event.target.checked,category.id)"
									>
								</label>
								<label 
									v-for="tag in category.tags"
									:key="tag.name + tag.id"
									:style="{backgroundColor: tag.color.name}"
									:class="{ 'disabled': disabled, 'opacity': activeCatTag !== 0 && !tag.active }"
								>
									<span v-show="tag.active">x</span>
									<span 
										v-text="tag.name"									
									></span>
									<input 
										type="checkbox"
										:checked="tag.active"
										@change="getLinksByTag($event.target.checked,tag.id)"
									>
								</label>
							</div>	
						</div>			
					</transition>				
				</div> 			
			</form>
		</section>
	   <section class="links masonry" is="transition-group" name="animation-2-list">	
	   	 <article 
	   	 	class="masonry-item"
 			v-for="link in linksList"
			:class="{done: link.status}"
			:key="link.id"
		>
		    <div class="masonry-content">
			    <nav>
			  		<label></label>		  			
			  		<div>
						<a 
							href="#" 
							@click.prevent="dataToEditor(link)"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M364.13 125.25L87 403l-23 45 44.99-23 277.76-277.13-22.62-22.62zM420.69 68.69l-22.62 22.62 22.62 22.63 22.62-22.63a16 16 0 000-22.62h0a16 16 0 00-22.62 0z"/>
							</svg>
						</a>
						<a 
							href="#"
							:class="{'disabled': disabled}"
			   				@click.prevent="deleteLink(link.id)"
			   			>
							<svg 
								v-if="!buttonPreloader"
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
		      <div class="masonry-description" v-html="link.content"></div>
		      <div class="masonry-footer">
		    	<div class="categories">
		    		<span 
						v-text="link.category.name"
					></span>
		    	</div>
		    	<div class="tags">
		    		<span 
						v-for="tag in link.tags"
						v-text="tag.name"
						:style="{backgroundColor: tag.color.name}"
					></span>
		    	</div>
		      </div>
		    </div>
		  </article>		
	   </section>
	   <section 
	   		class="pagination">
			<a 
				href="#"
				v-if="pagination.button.links"
				:class="{'disabled' : disabled}"
				@click.prevent="getLinksList()"
			>
				<span v-if="!buttonPreloader">+ 10</span>
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
				v-if="pagination.button.categories"
				:class="{'disabled' : disabled}"
				@click.prevent="getLinksByCategory(true,activeCatTag)"
			>
				<span v-if="!buttonPreloader">+ 10</span>
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
				v-if="pagination.button.tags"
				:class="{'disabled' : disabled}"
				@click.prevent="getLinksByTag(true,activeCatTag)"
			>
				<span v-if="!buttonPreloader">+ 10</span>
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
				v-if="pagination.button.linksLike"
				:class="{'disabled' : disabled}"
				@click.prevent="getLinksLike"
			>
				<span v-if="!buttonPreloader">+ 10</span>
				<svg 
					v-else
					xmlns="http://www.w3.org/2000/svg" 
					viewBox="0 0 512 512"
					class="animation-rotation">
					<path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/>
				</svg>
			</a>
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
							class="category_select"							
							>
							<option 
								v-for="cat in categories" 
								:value="cat.id"
								v-text="cat.name"
								:selected="cat.selected"
								:disabled="cat.disabled"
							></option>
						</select>
					</div>	
					<div 
						class="form_group_item"
						v-if="tags.length > 0"
					>
						<Multiselect							
							:tags="tags"
							:active_tags="currentLink.tags"
							@select_tag="selectTag"
						>
						</Multiselect>
					</div>
				</div>
				<transition-group mode="out-in">					
					<ckeditor 
						v-model="currentLink.content" 
						:config="editorConfig"
						@focus="onEditorFocus"
						key="ckeditor"
					></ckeditor>
				</transition-group>	
				<div v-if="validation">
					<transition>
						<button
							:class="{'disabled': disabled || !currentLink.content}"
							@click.prevent="addLink()"
							v-if="!editButton"						
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
							@click.prevent="editLink(
													currentLink,
													{ 
														content: currentLink.content 
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
					    @click.prevent="resetData()"
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
			activeCatTag: 0,	
			categories: [],
	        editorColorsList: false,
        	editButton: false,
        	editorConfig: {
	            height: 250
	        },
			categoriesToDisplay: [],
        	currentLink:{
				category: {
					id: 0,
					name: ''
				},
				tags: [],
				content: ''
			},
	        disabled: false,
			buttonPreloader: false,
			flash:{
				type: false,
				title: false,
				text: false,
				status: false
			},
	        linksList: [],
			pagination:{
				button:{
					links: false,
					categories: false,
					linksLike: false,
					tags: false
				},
				offset:{
					links: -10,
					categories: -10,
					linksLike: -10,
					tags: -10
				}
			},
			showCategoriesWithTags: false,
			searchData: '',
			searchDataOld: '',
			tags: [],			
        }
    },
    methods:{
    	dataToEditor(link){
    	 	this.editButton = true
			let _link = JSON.parse( JSON.stringify( link ) )
			this.categories = this.categories.map(cat => {
				cat.selected = cat.id === _link.category.id ? true : false
				return cat
			})
    	 	this.currentLink = _link
			const _cat = this.categories.find(cat => cat.id === _link.category.id)
			this.tags = _cat.tags	
			// this.tags.forEach(tag => tag.status = false)	
			this.tags = this.tags.reduce((acc,tag)=>{
				tag.status = false
				if(this.currentLink.tags.find(_tag => _tag.id === tag.id)){
					tag.status = true
				}
				acc.push(tag)
				return acc
			},[])
    	 	this.$refs['editor_section'].scrollIntoView({block: "center", behavior: "smooth"})
    	 },
		changeCategory(target){
			const id = Number(target.value)
			this.categories = this.categories.map(cat => {
				cat.selected = cat.id === id ? true : false
				return cat
			})
			const _cat = this.categories.find(cat => cat.id === id )	
			this.currentLink.category = _cat
			this.currentLink.tags = []
			this.tags = _cat.tags	
			this.tags.forEach(tag => tag.status = false)
		},
		toggleCategoriesWithTags(v){
			this.showCategoriesWithTags = !this.showCategoriesWithTags
			if(!v){
				this.activeCatTag = 0
				this.currentLink.tags = []
				this.categoriesToDisplay.forEach(cat => {
					cat.active = false
					if(cat.tags.length !== 0){
						cat.tags.forEach(tag => tag.active = false )
					}
				})
				Object.keys(this.pagination.button).forEach(key => this.pagination.button[key] = false )
		   		Object.keys(this.pagination.offset).forEach(key => this.pagination.offset[key] = -10 )
				this.getLinksList()
			}
		},
		selectTag(activeCatTag,status){
			this.tags.forEach(_tag => {
				if(_tag.id === activeCatTag.id){
					_tag.status = status
				}				
			})
			this.currentLink.tags = this.tags.filter(tag => tag.status)	
		},
		closeMultiselectList(target){
			if(!target.classList.contains("no-close")){
				this.$emit('closeMultiselectList', false)
			}	
		},
		onEditorFocus(){ 
			this.$emit('closeMultiselectList', false)		
		},
        resetData(){
			this.currentLink = { content: '', tags: [],  category: {id: 0, name: ''} } 
			this.tags = []
			this.editButton = false
			this.categories.forEach(cat => cat.selected = false)
			this.categories[0].selected = true
        },
		resetSearchData(){
			this.searchData = ''
			this.searchDataOld = ''
			this.getLinksList()
		},
		hideFlash(){
			Object.keys(this.flash).forEach(key => this.flash[key] = false)
		},
		getLinksList(){
		   NProgress.start()
		   this.disabled = true
		   this.buttonPreloader = true
		   Object.keys(this.pagination.button).forEach(key => { if(key !== 'links'){ this.pagination.button[key] = false } } )
		   Object.keys(this.pagination.offset).forEach(key => { if(key !== 'links'){ this.pagination.offset[key] = -10 } } )
		   this.pagination.offset.links = ( this.pagination.offset.links / 10 + 1 ) * 10
           ajax               
            .get('/links',{skip: this.pagination.offset.links})              
            .then(data =>  {
                    try{
						// console.log('data: ',data)
						const parsed = JSON.parse(data)
						this.linksList = this.pagination.offset.links === 0 ? parsed.links : this.linksList.concat(parsed.links)
						this.pagination.button.links = parsed.pagination_button
						NProgress.done()
                    }catch(e){
                        this.linksList = []
						this.categories = []
						alert('ERROR: SEE CONSOLE')
                        console.log('PARSING DATA ERROR: ',e.message)
                    }finally{
						this.disabled = false
						this.buttonPreloader = false
					}                   
                },             
                err => {
					alert('ERROR: SEE CONSOLE')
					this.disabled = false
					console.log('err: ',err) 
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
						this.categoriesToDisplay = JSON.parse( JSON.stringify (parsed.map(cat => {
							cat.active = false
							cat.tags = cat.tags.map(tag => {
								tag.active = false
								return tag
							})
							return cat
						})) )
                        this.categories = parsed.map(cat => {
							cat.selected = false
							cat.disabled = false
							return cat
						})						
						this.categories.unshift(
							{
								id:0,
								name:'категория',
								selected: true,
								disabled: true,
								tags: []
							}
						)
                    }catch(e){
						this.categories = []
						alert('ERROR: SEE CONSOLE')
                        console.log('PARSING DATA ERROR: ',e.message)
                    }
                    
                },             
                err => {
					console.log('err: ',err) 
					alert('ERROR: SEE CONSOLE') 
                }           
              )    	 	
		 },
		 getLinksByCategory(checkboxValue,catId){
			this.buttonPreloader = true
			this.disabled = true
		   if(!checkboxValue){
				this.categoriesToDisplay.forEach(cat => {
					cat.active = false
					if( cat.tags.length !== 0 ){
						cat.tags.forEach(tag => {
							tag.active = false
						})
			  		 }
				})
				this.activeCatTag = 0
				this.getLinksList()
				return
		   } 


		   this.pagination.button.links = false
		   this.pagination.button.tags = false
		   this.pagination.button.linksLike = false		   
		   if(catId !== 0 && catId !== this.activeCatTag){
			this.pagination.button.categories = false
		   }

		   this.pagination.offset.links = -10
		   this.pagination.offset.tags = -10
		   this.pagination.offset.linksLike = -10
		   if(catId !== 0 && catId !== this.activeCatTag){
			this.pagination.offset.categories = -10
		   }

		   this.activeCatTag = catId
		   this.categoriesToDisplay.forEach(cat => { 
			   cat.active = cat.id === catId ? true : false
			   if( cat.tags.length !== 0 ){
					cat.tags.forEach(tag => {
						tag.active = false
					})
			   }
		   })
		   this.pagination.offset.categories = ( this.pagination.offset.categories / 10 + 1 ) * 10
           ajax               
            .get('/links/get-by-cat',{skip: this.pagination.offset.categories, cat_id: catId})              
            .then(data =>  {
                    try{
						// console.log('data: ',data)
						const parsed = JSON.parse(data)
						this.linksList = this.pagination.offset.categories === 0 ? parsed.links : this.linksList.concat(parsed.links)
						this.pagination.button.categories = parsed.pagination_button
                    }catch(e){
                        this.linksList = []
                        this.categories = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE')
                    }finally{
						this.disabled = false
						this.buttonPreloader = false
					}                   
                },             
                err => {
					this.disabled = false
					console.log('err: ',err)  
					alert('ERROR: SEE CONSOLE')
                }           
              ) 
		 },
		 getLinksByTag(checkboxValue,tagId){
			this.buttonPreloader = true
			this.disabled = true
		   if(!checkboxValue){
				this.categoriesToDisplay.forEach(cat => { 
					if( cat.tags.length !== 0 ){
							cat.tags.forEach(tag => {
								tag.active = false
							})
					}
				})
				this.activeCatTag = 0
				this.getLinksList()
				return
		   } 

		   this.pagination.button.links = false
		   this.pagination.button.categories = false
		   this.pagination.button.linksLike = false
		   if(tagId !== 0 && tagId !== this.activeCatTag){
			this.pagination.button.tags = false
		   }

		   this.pagination.offset.links = -10
		   this.pagination.offset.categories = -10
		   this.pagination.offset.linksLike = -10
		   if(tagId !== 0 && tagId !== this.activeCatTag){
			this.pagination.offset.tags = -10
		   }

		   this.activeCatTag = tagId
		   this.categoriesToDisplay.forEach(cat => { 
			   cat.active = false
			   if( cat.tags.length !== 0 ){
					cat.tags.forEach(tag => {
						tag.active = false
						if(tag.id === tagId){
							tag.active = true
						}
					})
			   }
		   })

		   this.pagination.offset.tags = ( this.pagination.offset.tags / 10 + 1 ) * 10
           ajax               
            .get('/links/get-by-tag',{skip: this.pagination.offset.tags, tag_id: tagId})              
            .then(data =>  {
                    try{
						// console.log('data: ',data)
						const parsed = JSON.parse(data)
						this.linksList = this.pagination.offset.tags === 0 ? parsed.links : this.linksList.concat(parsed.links)
						this.pagination.button.tags = parsed.pagination_button
                    }catch(e){
                        this.linksList = []
                        this.categories = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE')
                    }finally{
						this.disabled = false
						this.buttonPreloader = false
					}                   
                },             
                err => {
					this.disabled = false
					console.log('err: ',err) 
					alert('ERROR: SEE CONSOLE') 
                }           
              )   
		 },
		getLinksLike(){

		   this.disabled = true
		   this.buttonPreloader = true

		   Object.keys(this.pagination.button).forEach(key => { if(key !== 'linksLike'){ this.pagination.button[key] = false } } )

		   Object.keys(this.pagination.offset).forEach(key => { if(key !== 'linksLike'){ this.pagination.offset[key] = -10 } } )

		   if(this.searchDataOld !== this.searchData){
				this.pagination.offset.linksLike = 0
		   }else{
				this.pagination.offset.linksLike = ( this.pagination.offset.linksLike / 10 + 1 ) * 10
		   }

		   this.searchDataOld = this.searchData 

           ajax               
            .get('/links/get-links-like',{skip: this.pagination.offset.linksLike, like: this.searchData})              
            .then(data =>  {
                    try{
						// console.log('data: ',data)
						const parsed = JSON.parse(data)
						this.linksList = this.pagination.offset.linksLike === 0 ? parsed.links : this.linksList.concat(parsed.links)
						this.pagination.button.linksLike = parsed.pagination_button
                    }catch(e){
                        this.linksList = []
                        this.categories = []
						console.log('PARSING DATA ERROR: ',e.message)
						alert('ERROR: SEE CONSOLE')
                    }finally{
						this.disabled = false
						this.buttonPreloader = false
					}                   
                },             
                err => {
					this.disabled = false
					console.log('err: ',err)  
					alert('ERROR: SEE CONSOLE')
                }           
              )    	 	
		 },
   	 	addLink(){
    	 	this.disabled = true
			this.ajaxMarker = true
			this.link = JSON.parse( JSON.stringify( this.currentLink ) )
			this.link.tags = this.currentLink.tags.map(tag => tag.id)
			this.link.category_id = this.currentLink.category.id
    	 	ajax.post('/link/add',this.link,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			// console.log('addLink parsed: ',parsed)
	            			if(parsed.status){
								const newLink = {}
								newLink.id = parsed.id	
								newLink.category = this.categories.find(cat => cat.id === this.currentLink.category.id)
								newLink.content = this.currentLink.content
								newLink.tags = this.tags.filter(tag => tag.status)
	            				this.linksList.push(newLink)
			                    this.resetData()
								this.flash = { type: "success", title: "done", status: true }      				
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	            		}finally{
	            			this.disabled = false
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
        editLink(currentLink){      	
        	this.disabled = true
        	this.ajaxMarker = true
			this.link = JSON.parse( JSON.stringify( this.currentLink ) )
			this.link.tags = this.currentLink.tags.map(tag => tag.id)
			this.link.category_id = this.currentLink.category.id
    	 	ajax.put('/link/update',this.link,this.csrf)              
	            .then(data =>  {
	            		// console.log('data editLink: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
	            				// console.log('parsed editLink: ',parsed)
					            this.linksList.forEach((link,i)=>{
					                if(link.id === this.currentLink.id){
					                	this.linksList[i] = this.currentLink
					                }               
					            })  
								this.resetData()
			                    this.flash = { type: "success", title: "done", status: true }
	            			}	            			
	            		}catch(e){
	            			console.log('PARSING DATA ERROR: ',e.message)
							this.flash = { type: "danger", title: "error", text: 'see console for details', status: true } 
	            		}finally{
	            			this.disabled = false
	            			this.editButton = false	
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
    	 deleteLink(id){
    	 	this.disabled = true
			this.buttonPreloader = true
    	 	ajax.delete('/link/delete',id,this.csrf)              
	            .then(data =>  {
	            		// console.log('data: ',data)
	            		try{
	            			const parsed = JSON.parse(data)
	            			if(parsed.status){
					            const index = this.linksList.findIndex( link => link.id === id )   
                                this.linksList.splice(index,1) 	            				
	            			}	            			
	            		}catch(e){
							console.log('PARSING DATA ERROR: ',e.message)
							alert('ERROR: SEE CONSOLE')
	            		}finally{
	            			this.disabled = false 
							this.buttonPreloader = false
	            		}
	            		
	                },             
	                err => {
	                	this.disabled = false
						this.buttonPreloader = false
						console.log('err: ',err)   
						alert('ERROR: SEE CONSOLE')
	                }           
	              )     	 	
		 }
    },
    computed:{
    	validation(){
    		  const r = [this.currentLink.content,
	    			this.currentLink.category.id !== 0,
	    			this.currentLink.tags.length !== 0,
		    		].filter(item => item)
		    	return r.length === 3
        }	
    },
    mounted(){
		this.getLinksList()			
		this.getCategories()
		this.H.gridMasonry()
    }
};
</script>
