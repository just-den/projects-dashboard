<aside
	:class="{ 'v-mobile-nav': mobileNavVar }">
	<div 
		class="mobile_nav"
		@click.prevent="mobileNav()"
	>
		<a href="javascript:void(0)">
			<span>&#8592;</span>		
		</a>
	</div>
	<div class="date">
	    <div class="point"></div>
	    <div class="point"></div>
	    <div class="day" v-text="currentDate.day"></div>
	    <div>
	    	<span v-text="currentDate.month"></span>
	        <span v-text="currentDate.year"></span>
	     </div>		
	</div>
	<ul class="main_nav">
		<li>
			<router-link to="/panel/agenda" exact>agenda</router-link>
		</li>
		<li>
			<router-link to="/panel/events">events</router-link>
		</li>
		<li>
			<router-link to="/panel/links">links</router-link>
		</li>
		<li>
			<router-link to="/panel/finance">finance</router-link>
		</li>
		<li>
			<router-link to="/panel/settings">settings</router-link>
		</li>
	</ul>
	<div class="auth">
		<a href="javascript:void(0)">
			<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
				<path d="M24 4C12.95 4 4 12.95 4 24s8.95 20 20 20 20-8.95 20-20S35.05 4 24 4zm0 6c3.31 0 6 2.69 6 6 0 3.32-2.69 6-6 6s-6-2.68-6-6c0-3.31 2.69-6 6-6zm0 28.4c-5.01 0-9.41-2.56-12-6.44.05-3.97 8.01-6.16 12-6.16s11.94 2.19 12 6.16c-2.59 3.88-6.99 6.44-12 6.44z"/>
			</svg>
			<span>Log Out</span>
		</a>
	</div>
</aside>