//共通バリデーションモジュール
function email(form) {
    const reg = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}.[A-Za-z0-9]{1,}$/ //メールアドレスの形式を定義

    var n = ''
    var n = form.email.length //emailの文字数を取得

    if (n == 0) {
        var message = "入力してください。"
    } else if (reg.test(form.email) == false) { //メールアドレスの形式になっているかチェック
        var message = "メールアドレスの形式で入力してください。"
    } else if (n > 256) {
        var message = "255文字以内で入力してください。"
    } else {
        var message = ""
    }

    if (message == "") {
        var result = true
    } else {
        var result = false
    } //emailの入力に問題がなければtrueを返す

    return {
        'result': result,
        'message': message
    }
}

function password(form) {
    //主にログインや新規登録時のパスワードのバリデーション 
    var n = ''
    var n = form.password.length //passwordの文字数

    console.log(n)

    if (n == 0) {
        var message = "入力してください。"
    } else if (n > 256) {
        var message = "255文字以内で入力してください。"
    } else {
        var message = ""
    }

    if (message == "") {
        var result = true
    } else {
        var result = false
    } //passwordの入力に問題がなければtrueを返す
    return {
        'result': result,
        'message': message
    }
}

function checkhoge(value) {
    //主にパスワード変更時に使う
    if (value == null || value.length == 0) {
        return "入力してください。"
    } else if (value.length > 256) {
        return "255文字以内で入力してください。"
    } else {
        return ""
    }
}

function name(form) {
    var n = ''
    var n = form.name.length //passwordの文字数

    if (n == 0) {
        var message = "入力してください。"
    } else if (n > 256) { //文字数を変数にしたい
        var message = "255文字以内で入力してください。"
    } else {
        var message = ""
    }

    if (message == "") {
        var result = true
    } else {
        var result = false
    } //nameの入力に問題がなければtrueを返す
    return {
        'result': result,
        'message': message
    }
}

export {
    name,
    email,
    password,
    checkhoge,
}
