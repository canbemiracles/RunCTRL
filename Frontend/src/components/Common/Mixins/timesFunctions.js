const timeFuncions = {
    methods:{
        timeToMinutes(time){
            let t = time.split(':');
            return parseInt(t[0]*60) + parseInt(t[1]);
        },
        //Проверка пересечения диапазонов времени
        isIntersecting(t1, t2){
            var t1_min = {
                begin: this.timeToMinutes(t1.time_open)-1,
                end: this.timeToMinutes(t1.time_close)-1
            };
            var t2_min = {
                begin: this.timeToMinutes(t2.time_open),
                end: this.timeToMinutes(t2.time_close)
            };
			if(t2_min.begin<=t1_min.end && t2_min.end >= t1_min.begin){
                return true;
            }else{
                return false;
            }
        },
    }
}
export default timeFuncions;