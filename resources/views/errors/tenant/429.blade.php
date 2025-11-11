@extends('errors.layouts.tenant')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('description', __('Lượng truy cập quá lớn. Vui lòng thử lại sau!'))
