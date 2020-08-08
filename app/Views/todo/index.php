<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"/>

    <title>Todo List</title>
    <style>
        .checked{text-decoration:line-through; text-decoration-color:red;}
    </style>
  </head>
  <body>
    
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <div class="input-group input-group-lg">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">@</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Todo ...." autofocus id="todo">
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 offset-md-3">
                <ul class="list-group" id="todos">
                </ul>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('assets/js/jquery-3.5.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/popper.min.js'); ?>" ></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>" ></script>
    <script src="<?php echo base_url('assets/js/notify.min.js'); ?>" ></script>

    <script>
        $(()=>{
            console.clear();
            //todo add
            $('#todo').keyup(function(event){
                const todo = $(this).val();
                if(event.which == 13){   //ENTER KEY
                    $.post('<?php echo base_url('todo-add'); ?>', {todo:todo}, function(response){
                        if(response == true){
                            $.notify('İşlem başarılı', 'success');
                            getTodos();
                            $('#todo').val('');
                        }else{
                            $.notify('İşlem başarısız', 'error');
                        }
                    },'json');
                }
            });

            //todo delete
            $(document).on('click', '.delete-todo', function(){
                const id = $(this).data('id');
                $.post('<?php echo base_url('delete-todo'); ?>', {id:id}, function(response){
                    $.notify('İşlem başarılı', 'success');
                    getTodos();
                },'json');
            });

            //todo complete uncomplete update
            $(document).on('change', '.complete', function(){
                const id        = $(this).data('id');
                const status    = $(this).data('status') == 'complete' ? 'uncomplete' : 'complete'; 
                $.post('<?php echo base_url('todo-complete-change'); ?>', {id:id, status:status}, function(response){
                    if(response){
                        getTodos();
                        $.notify('İşlem başarılı', 'success');
                    }else{
                        $.notify('İşlem başarısız', 'danger');
                    }
                }, 'json')
            });

            //get todos
            getTodos();
        });

        function getTodos(){
            $.get('<?php echo base_url('get-todos'); ?>', function(todos){
                let dataHTML = `<li class="list-group-item d-flex justify-content-between align-items-center list-group-item-success font-weight-bold">
                                    YAPILACAKLAR
                                </li>`;
                let isChecked   = '';
                todos.forEach((todo, index)=>{
                    isChecked   = todo.status == 'complete' ? 'checked' : ''; 
                    index++;
                    dataHTML += `
                        <li class="list-group-item">
                            <b>${index}</b> - <label class="${isChecked}" for="complete-checkbox-${todo.id}">${todo.todo}</label>
                            <span class="float-right">
                                <input type="checkbox" class="form-check-input complete" data-id="${todo.id}" data-status="${todo.status}" id="complete-checkbox-${todo.id}" ${isChecked}> 
                                <i class="far fa-trash-alt text-danger delete-todo" style="cursor:pointer" data-id="${todo.id}"></i>
                            </span>
                        </li>
                    `;
                });

                $('#todos').html(dataHTML);
            }, 'json')
        }
    </script>
  </body>
</html>