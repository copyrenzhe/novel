/*
    寻找attr为val的对象是否在arrayToSearch对角数组中
 */
function findElem(arrayToSearch,attr,val){
    for (var i=0;i<arrayToSearch.length;i++){
        if(arrayToSearch[i][attr]==val){
            return i;
        }
    }
    return -1;
}
/*
    对象数组Array 根据对象object key的值排序
    desc: true|false 是否倒序
    example: array.sort(keysrt('updated_at', true))
 */
function keysrt(key,desc) {
    return function(a,b){
        return desc ? ~~(a[key] < b[key]) : ~~(a[key] > b[key]);
    }
}