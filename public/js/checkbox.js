const ajaxSelect = (id, project_id) => {
    // AjaxでTasksControllerのstoreメソッドを呼ぶ
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: 'tasks/ajax',
        type: 'POST',
        data: {
                'id':id, 
                'project_id':project_id, 
                '_method': 'POST', 
                status:"select"
        },
    })
    // Ajaxリクエストが成功した場合
    .done(function(data) {
        console.log("success select");
        let temp = JSON.stringify(data);
        window.location.reload(true);
    })
    // Ajaxリクエストが失敗した場合
    .fail(function(data) {
        alert(data.responseJSON);
    });
};

$('.check').change(function() {
    // 選択したtaskのid
    const taskId = $(this).val();
    const projectId = $(this).attr("name");
    
    ajaxSelect(taskId, projectId);
    
});