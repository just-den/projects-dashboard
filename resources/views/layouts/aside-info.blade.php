<aside
	:class="{ 'v-mobile-info': mobileInfoVar }">
	<section 
		class="mobile_nav"
		@click.prevent="mobileInfo()"
	>
		<a href="javascript:void(0)">
			<span>&#8594;</span>		
		</a>
	</section>
	<section class="currency">
		<ul>
			<li v-for="currency in currencies" :class="currency.ccy">
				<span class="sign" v-text="currency.sign"></span>
				<div class="price">
					<span v-text="currency.buy"></span> - <span v-text="currency.sale"></span>
				</div>				
			</li>
		</ul>
	</section>
	<section class="weather">
		<ul>
			<li v-for="data in weather">
				<a href="javascript:void(0)" target=_blank>
					<span v-text="data.city_en"></span>
					<span v-text="data.temperature"></span>
					<div v-html="data.icon"></div>
				</a>
			</li>
		</ul>
	</section>
	<section class="password_generator">
		<div>
			<nav>
				<label
					:class="{ active: password.settings.length == 6 }"
				>
					<div class="point"></div>
                    <span>6</span>
					<input 
						type="radio" 
						name="length"
						:checked="password.settings.length == 6"
						@change="password.settings.length = 6"
					>
				</label>
				<label 
					:class="{ active: password.settings.length == 12 }"
				>
					<div class="point"></div>
					<span>12</span>
					<input 
						type="radio" 
						name="length" 
						:checked="password.settings.length == 12"
						@change="password.settings.length = 12"
					>
				</label>
				<label
					:class="{ active: password.settings.plain }"
				>
					<div class="point"></div>
					<span>plain</span>
					<input 
						type="checkbox"
						@change="password.settings.plain = !password.settings.plain" 
						:checked="password.settings.plain"
					>
				</label>
			</nav>
			<div class="input_group">
				<input 
					type="text" 
					id="password_new"
					:value="password.new" 
					readonly
				>
				<a 
					href="javscript:void(0)" 
					title="new password"
					@click="newPassword()"
				>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
						<path d="M24 8V2l-8 8 8 8v-6c6.63 0 12 5.37 12 12 0 2.03-.51 3.93-1.39 5.61l2.92 2.92C39.08 30.05 40 27.14 40 24c0-8.84-7.16-16-16-16zm0 28c-6.63 0-12-5.37-12-12 0-2.03.51-3.93 1.39-5.61l-2.92-2.92C8.92 17.95 8 20.86 8 24c0 8.84 7.16 16 16 16v6l8-8-8-8v6z"/>
					</svg>					
				</a>
				<a 
					href="javscript:void(0)" 
					title="copy"
					@click="copyPassword()"
				>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
						<path d="M32 2H8C5.79 2 4 3.79 4 6v28h4V6h24V2zm6 8H16c-2.21 0-4 1.79-4 4v28c0 2.21 1.79 4 4 4h22c2.21 0 4-1.79 4-4V14c0-2.21-1.79-4-4-4zm0 32H16V14h22v28z"/>
					</svg>
					<span v-if="password.copied">copied!</span>
				</a>
			</div>
		</div>
	</section>
</aside>	

