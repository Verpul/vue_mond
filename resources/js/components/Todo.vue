<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12 mt-3">
        <!-- TO DO List -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="ion ion-clipboard mr-1"></i>
              Задачи
            </h3>

            <div class="card-tools">
              <ul class="pagination pagination-sm">
                <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                <li class="page-item"><a href="#" class="page-link">1</a></li>
                <li class="page-item"><a href="#" class="page-link">2</a></li>
                <li class="page-item"><a href="#" class="page-link">3</a></li>
                <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
              </ul>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <ul class="todo-list" data-widget="todo-list">
              <li>
                <!-- drag handle -->
                <span class="handle">
                  <i class="fas fa-ellipsis-v"></i>
                  <i class="fas fa-ellipsis-v"></i>
                </span>
                <!-- checkbox -->
                <div  class="icheck-primary d-inline ml-2">
                  <input type="checkbox" value="" name="todo1" id="todoCheck1">
                  <label for="todoCheck1"></label>
                </div>
                <!-- todo text -->
                <span class="text">Отчет:</span>
                <span class="text">Сделать отчет РИСМ</span>
                <!-- Emphasis label -->
                <small class="badge badge-danger"><i class="far fa-clock"></i> Высокий до 06.05.2020</small>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                  <i class="fas fa-edit"></i>
                  <i class="fas fa-trash-o"></i>
                </div>
              </li>
              <li>
                <!-- drag handle -->
                <span class="handle">
                  <i class="fas fa-ellipsis-v"></i>
                  <i class="fas fa-ellipsis-v"></i>
                </span>
                <!-- checkbox -->
                <div  class="icheck-primary d-inline ml-2">
                  <input type="checkbox" value="" name="todo1" id="todoCheck1">
                  <label for="todoCheck1"></label>
                </div>
                <!-- todo text -->
                <span class="text">Медиалог:</span>
                <span class="text">Объеденить пациентов 2532 и 12323</span>
                <!-- Emphasis label -->
                <small class="badge badge-warning"><i class="far fa-clock"></i> Средний </small>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                  <i class="fas fa-edit"></i>
                  <i class="fas fa-trash-o"></i>
                </div>
              </li>
              <li>
                <!-- drag handle -->
                <span class="handle">
                  <i class="fas fa-ellipsis-v"></i>
                  <i class="fas fa-ellipsis-v"></i>
                </span>
                <!-- checkbox -->
                <div  class="icheck-primary d-inline ml-2">
                  <input type="checkbox" value="" name="todo1" id="todoCheck1">
                  <label for="todoCheck1"></label>
                </div>
                <!-- todo text -->
                <span class="text">Сайт:</span>
                <span class="text">Изменить информацию по приему граждан</span>
                <!-- Emphasis label -->
                <small class="badge badge-info"><i class="far fa-clock"></i> Низкий до 01.05.2021 </small>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                  <i class="fas fa-edit"></i>
                  <i class="fas fa-trash-o"></i>
                </div>
              </li>
            </ul>  
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button type="button" class="btn btn-primary float-right" @click="showModal = true"><i class="fas fa-plus"></i> Добавить</button>
          </div>
        </div>
      </div>
    </div>
    <todo-modal v-if="showModal" @close="showModal = false"></todo-modal>
  </div>
</template>

<script>
  import TodoModal from './TodoModal';

  export default {
      name: 'Todo',
      data(){
        return {
          showModal: false,
        }
      },
      components: {
        'todo-modal': TodoModal
      }
  }
</script>
