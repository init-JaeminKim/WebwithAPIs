function noSpace(obj) {
    var str_space = /\s/;               // 공백 체크
    var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/gi; // 특수문자 체크

    if (str_space.exec(obj.value)) {     // 공백 체크
        alert("No Space!");
        obj.focus();
        obj.value = obj.value.replace(' ', ''); // 공백제거
        return false;
    }
    else if (regExp.exec(obj.value)) {
        alert("No Spacial Character!");
        obj.focus();
        obj.value = obj.value.replace(obj.value, ''); // 공백제거
        return false;
    }
}

function checkVal() {

    var id = document.forms["keyValue"]["id"].value;
    var pwd = document.forms["keyValue"]["pwd"].value;

    if (id == '' || id == null || pwd == '' || pwd == null) {
        alert("Empty values");
        return false;
    }
}