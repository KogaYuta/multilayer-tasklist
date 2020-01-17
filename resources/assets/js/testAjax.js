$.ajax({
  url: "js/testAjaxGetted.js", //絶対パスで指定する
  type: "GET",
  dataType: "script"
}).done(function(script) {
// 文字列をjavascriptとして実行。
// 読み込まれたjavscriptをいろいろ使う
  console.log(text);
});