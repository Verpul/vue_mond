<template>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
              <div class="row justify-content-between">
              <h3 class="card-title ml-2">Сотрудники</h3>
              <button class="btn btn-success mr-2" @click="newEmployee">Добавить
                <i class="fas fa-user-plus"></i>
              </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row justify-content-between mb-3">
                <div class="col-2">
                  <select class="form-control form-control-sm">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                  </select>
                </div>
                <div class="col-2">
                  <input type="text" name="search" class="form-control form-control-sm"
                  placeholder="Поиск">
                </div>
              </div>
              <div class="row">
                <table class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Должность</th>
                    <th>Email</th>
                    <th>Рабочий телефон</th>
                    <th>Мобильный телефон</th>
                    <th>Изменить</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="employee in employees" :key="employee.id">
                    <td>{{employee.id}}</td>
                    <td>{{showFullName(employee)}}</td>
                    <td>{{employee.position}}</td>
                    <td>{{employee.email}}</td>
                    <td>{{employee.work_phone}}</td>
                    <td>{{employee.mobile_phone}}</td>
                    <td>
                      <button @click="editEmployee(employee)" class="btn btn-sm">
                        <i class="fas fa-user-edit"></i>
                      </button>|
                      <button @click="deleteEmployee(employee.id)" class="btn btn-sm">
                        <i class="fas fa-user-minus"></i>
                      </button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" 
    aria-labelledby="employeeModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="employeeModalLabel" v-if="!editMode">Добавить сотрудника</h5>
            <h5 class="modal-title" id="employeeModalLabel" v-if="editMode">Редактировать данные сотрудника</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateEmployee() : createEmployee()">
                  <div class="modal-body">
                    <div class="form-group">
                      <input v-model="form.last_name" type="text" name="last_name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }"
                        placeholder="Фамилия сотрудника">
                      <has-error :form="form" field="last_name"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.first_name" type="text" name="first_name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }"
                        placeholder="Имя сотрудника">
                      <has-error :form="form" field="first_name"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.middle_name" type="text" name="middle_name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('middle_name') }"
                        placeholder="Отчество сотрудника">
                      <has-error :form="form" field="middle_name"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.position" type="text" name="position"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('position') }"
                        placeholder="Должность сотрудника">
                      <has-error :form="form" field="position"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.email" type="text" name="email"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }"
                        placeholder="E-mail сотрудника">
                      <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.work_phone" type="text" name="work_phone"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('work_phone') }"
                        placeholder="Рабочий телефон сотрудника">
                      <has-error :form="form" field="work_phone"></has-error>
                    </div>
                    <div class="form-group">
                      <input v-model="form.mobile_phone" type="text" name="mobile_phone"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('mobile_phone') }"
                        placeholder="Мобильный телефон сотрудника">
                      <has-error :form="form" field="mobile_phone"></has-error>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary" v-if="editMode">Обновить</button>
                    <button type="submit" class="btn btn-primary" v-if="!editMode">Создать</button>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Employees',
  data(){
    return {
      editMode: false,
      employees: {},
      form: new Form({
        id: '',
        last_name: '',
        middle_name: '',
        first_name: '',
        position: '',
        email: '',
        work_phone: '',
        mobile_phone: ''
      })
    }
  },
  methods: {
    loadData(){
      axios.get('/api/employees')
      .then((response) => {
        this.employees = response.data
      })
    },
    showFullName(employee){
      return employee.last_name+' '+employee.first_name+' '+employee.middle_name;
    },
    newEmployee(){
      this.editMode = false;
      this.form.clear();
      this.form.reset();
      $('#employeeModal').modal('show');
    },
    createEmployee(){
      this.form.post('/api/employees')
      .then(() => {
        this.loadData();
        $('#employeeModal').modal('hide');

        this.$swal({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            icon: 'success',
            title: 'Новый сотрудник добавлен!'
        });
      })
    },
    editEmployee(employee){
      this.editMode = true;
      this.form.clear();
      this.form.reset();
      $('#employeeModal').modal('show');
      this.form.fill(employee);
    },
    updateEmployee(){
      this.form.put('/api/employees/' + this.form.id)
      .then(() => {
          this.loadData();

          $('#employeeModal').modal('hide');

          this.$swal({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              icon: 'success',
              title: 'Информация обновлена!'
          });
      })
    },
    deleteEmployee(id){
      this.$swal({
        title: 'Вы уверены?',
        text: "Данные нельзя будет восстановить!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена'
        }).then((result) => {
            if (result.value) {
                //Send delete request
                this.form.delete('/api/employees/'+id).then(() => {
                    //reload data table
                    this.loadData();

                    this.$swal(
                      'Удалено!',
                      'Сотрудник был удален',
                      'success'
                    )    
                }).catch(() => {
                    this.$swal(
                        'Действие отменено',
                        'Что-то пошло не так',
                        'error'
                    )
                });
            }       
      })
    }
  },
  created(){
    this.loadData();
  },
}
</script>