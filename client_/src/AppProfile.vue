<template>
	<div class="layout-profile">
		<!-- <img src="./assets/olige.png"> -->
			<h4>Room Booking System</h4>
		
	</div>
</template>

<script>
	export default {
		data() {
			return {
				expanded: false
			}
		},
		methods: {
			onClick(event){
				this.expanded = !this.expanded;
				event.preventDefault();
			}
		}
	}
</script>

<style scoped>

</style>