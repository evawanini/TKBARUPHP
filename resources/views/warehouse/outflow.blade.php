@extends('layouts.adminlte.master')

@section('title')
    @lang('warehouse.outflow.index.title')
@endsection

@section('page_title')
    <span class="fa fa-mail-reply fa-rotate-90 fa-fw"></span>&nbsp;@lang('warehouse.outflow.index.page_title')
@endsection
@section('page_title_desc')
    @lang('warehouse.outflow.index.page_title_desc')
@endsection
@section('breadcrumbs')
    {!! Breadcrumbs::render('outflow') !!}
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div ng-app="warehouseOutflowModule" ng-controller="warehouseOutflowController">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('warehouse.outflow.index.header.warehouse')</h3>
            </div>
            <div class="box-body">
                <select id="inputWarehouse"
                        class="form-control"
                        ng-model="warehouse"
                        ng-options="warehouse as warehouse.name for warehouse in warehouseDDL track by warehouse.id"
                        ng-change="getWarehouseSOs(warehouse)">
                    <option value="">@lang('labels.PLEASE_SELECT')</option>
                </select>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('warehouse.outflow.index.header.sales_order')</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">@lang('warehouse.outflow.index.table.header.code')</th>
                        <th class="text-center">@lang('warehouse.outflow.index.table.header.so_date')</th>
                        <th class="text-center">@lang('warehouse.outflow.index.table.header.customer')</th>
                        <th class="text-center">@lang('warehouse.outflow.index.table.header.shipping_date')</th>
                        <th class="text-center">@lang('labels.ACTION')</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="so in SOs">
                        <td class="text-center">@{{ so.code }}</td>
                        <td class="text-center">@{{ so.so_created }}</td>
                        <td ng-show="so.customer_type == 'CUSTOMERTYPE.R'" class="text-center">@{{ so.customer.name }}</td>
                        <td ng-show="so.customer_type == 'CUSTOMERTYPE.WI'" class="text-center">@{{ so.walk_in_cust }}</td>
                        <td class="text-center">@{{ so.shipping_date }}</td>
                        <td class="text-center" width="20%">
                            <a class="btn btn-xs btn-primary" href="{{ route('db.warehouse.outflow') }}/@{{ so.id }}" title="Deliver"><span class="fa fa-pencil fa-fw"></span></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('custom_js')
    <script type="application/javascript">
        var app = angular.module('warehouseOutflowModule', []);
        app.controller('warehouseOutflowController', ['$scope', '$http', function($scope, $http) {
            $scope.warehouseDDL = JSON.parse('{!! htmlspecialchars_decode($warehouseDDL) !!}');

            $scope.SOs = [];

            $scope.getWarehouseSOs = function (warehouse) {
                $http.get('{{ route('api.warehouse.outflow.so') }}/' + warehouse.id).success(function (data) {
                    $scope.SOs = data;
                });
            }
        }]);
    </script>
@endsection
