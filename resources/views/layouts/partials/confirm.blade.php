bootbox.confirm({
message: 'This is a confirm with custom button text and color! Do you like it?',
buttons: {
confirm: {
label: 'Yes',
className: 'btn-success'
},
cancel: {
label: 'No',
className: 'btn-danger'
}
},
callback: function (result) {
if(result){
form.submit();
};
console.log('This was logged in the callback: ' + result);

}
});