
let devicesInMenu = {
    'curentDeviceId': undefined,
    'addCkickOnMenuEventListener' : function () {

        let self = this;

        let lists = document.getElementsByClassName('devices-list');

        let count_el = lists.length;

        if (count_el > 0){
            for(let i = 0; i < count_el; i ++){
                lists[i].addEventListener('click',function(){
                    for(let j = 0; j < count_el; j ++){
                        lists[j].classList.remove('active');
                    }
                    this.classList.add('active');
                    self.curentDeviceId = this.dataset.id;
                });
            }
        }
    }
}