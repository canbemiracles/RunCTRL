<template>
    <Schedule 
            :branchId="branchId"
            :type="type"
			>
	</Schedule>
</template>
<script>
import Schedule from './ScheduleSrc/Schedule';
export default {
	props:{ 
        branchId: Number,
        type: String
	},
    data: function() {
        return {
        }
    },
    components: {
       Schedule: Schedule,
	},
    methods: {
		
    },
}
</script>