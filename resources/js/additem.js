window.addEventListener('DOMContentLoaded', (e)=>{
    document.getElementById('cb-repeat').onchange = function(){
        document.getElementById('repeat-number').disabled = !this.checked;
        document.getElementById('repeat-unit').disabled = !this.checked;
    }
})

