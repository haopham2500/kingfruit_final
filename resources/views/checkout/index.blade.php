@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h2 class="fw-bold mb-4 text-success">
        THANH TOÁN ĐƠN HÀNG
    </h2>

    <form action="{{ route('checkout.placeOrder') }}" method="POST">

        @csrf

        <div class="row">

            <!-- FORM -->

            <div class="col-md-7">

                <div class="card shadow-sm border-0 p-4">

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Họ và tên
                        </label>

                        <input type="text"
                               name="customer_name"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Số điện thoại
                        </label>

                        <input type="text"
                               name="phone"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Địa chỉ giao hàng
                        </label>

                        <textarea name="address"
                                  rows="3"
                                  class="form-control"
                                  required></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Ghi chú
                        </label>

                        <textarea name="note"
                                  rows="3"
                                  class="form-control"></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Phương thức thanh toán
                        </label>

                        <select name="payment_method"
                                class="form-select">

                            <option value="cod">
                                Thanh toán khi nhận hàng
                            </option>

                            <option value="banking">
                                Chuyển khoản ngân hàng
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            <!-- ĐƠN HÀNG -->

            <div class="col-md-5">

                <div class="card shadow-sm border-0 p-4">

                    <h4 class="fw-bold mb-4">
                        Đơn hàng của bạn
                    </h4>

                    @php
                        $total = 0;
                    @endphp

                    @foreach(session('cart', []) as $id => $item)

                        @php
                            $subtotal = $item['price'] * $item['quantity'];

                            $total += $subtotal;
                        @endphp

                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                {{ $item['name'] }}
                                x {{ $item['quantity'] }}
                            </span>

                            <span class="fw-bold text-danger">
                                {{ number_format($subtotal) }} đ
                            </span>

                        </div>

                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between">

                        <h5 class="fw-bold">
                            Tổng tiền:
                        </h5>

                        <h5 class="fw-bold text-danger">
                            {{ number_format($total) }} đ
                        </h5>

                    </div>

                    <button type="submit"
                            class="btn btn-success w-100 mt-4 py-3 fw-bold">

                        XÁC NHẬN ĐẶT HÀNG

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection