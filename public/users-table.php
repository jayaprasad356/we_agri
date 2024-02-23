
<section class="content-header">
    <h1>Users /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
            <ol class="breadcrumb">
                <a class="btn btn-block btn-default" href="add-users.php"><i class="fa fa-plus-square"></i> Add New User</a>
</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <div class="col-md-2">
                        <h4 class="box-title">Referred By</h4>
                            <input type="text" class="form-control" name="referred_by" id="referred_by" >
                        </div>
                        </div>
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="name" data-sortable="true">Name</th>
                                    <th  data-field="mobile" data-sortable="true">Mobile</th>
                                    <th  data-field="email" data-sortable="true">Email</th>
                                    <th data-field="age" data-sortable="true">Age</th>
                                    <th  data-field="city" data-sortable="true">City</th>
                                    <th  data-field="state" data-sortable="true">State</th>
                                    <th data-field="refer_code" data-sortable="true">Refer Code</th>
                                    <th data-field="referred_by" data-sortable="true">Referred By</th>
                                    <th data-field="recharge" data-sortable="true">Recharge </th>
                                    <th  data-field="balance" data-sortable="true">Balance</th>
                                    <th  data-field="total_income" data-sortable="true">Total Income</th>
                                    <th data-field="today_income" data-sortable="true">Today Income</th>
                                    <th data-field="device_id" data-sortable="true">Device ID</th>
                                    <th  data-field="account_num" data-sortable="true">Account Number</th>
                                    <th  data-field="holder_name" data-sortable="true">Holder Name</th>
                                    <th  data-field="bank" data-sortable="true">Bank</th>
                                    <th data-field="branch" data-sortable="true">Branch</th>
                                    <th  data-field="ifsc" data-sortable="true">IFSC</th>
                                    <th  data-field="total_recharge" data-sortable="true">Total Recharge</th>
                                    <th data-field="team_size" data-sortable="true">Team Size</th>
                                    <th data-field="valid_team" data-sortable="true">Valid Team</th>
                                    <th data-field="total_assets" data-sortable="true">Total Assets</th>
                                    <th  data-field="total_withdrawal" data-sortable="true">Total Withdrawals</th>
                                    <th  data-field="team_income" data-sortable="true">Team Income</th>
                                    <th data-field="registered_datetime" data-sortable="true">Registered Datetime</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>

<script>

    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#trail_completed').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#referred_by').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#plan').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    function queryParams(p) {
        return {
            "date": $('#date').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            "trail_completed": $('#trail_completed').val(),
            "referred_by": $('#referred_by').val(),
            "plan": $('#plan').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>
