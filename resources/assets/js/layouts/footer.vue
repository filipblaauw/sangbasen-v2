<template>
	<footer class="footer d-print-none">
        {{getConfig('footer_credit')}}
    </footer>
</template>

<script>
    export default {
    	methods: {
    		getConfig(config){
    			return helper.getConfig(config);
    		}
    	}
    }
</script>
