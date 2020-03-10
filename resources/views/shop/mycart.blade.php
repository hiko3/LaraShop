@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="alert alert-success">
      {{ session('flash_message') }}
    </div>
  @endif
<div class="container-fluid">
  <div class="">
      <div class="mx-auto" style="max-width:1200px">
          <h1 class="text-center font-weight-bold" style="color:#555555;  font-size:1.2em; padding:24px 0px;">
          {{ Auth::user()->name }}さんのカートの中身</h1>

          <div class="card-body">
              @if ($myCarts->isNotEmpty())

                @foreach($myCarts as $myCart)
                    <div class="mycart_box">
                      {{ $myCart->stock->name }} <br>
                      {{ number_format($myCart->stock->fee) }}円 <br>
                        <img src="/image/{{$myCart->stock->imgpath}}" class="incart">
                        <form action="{{ route('mycart.delete', $myCart->id) }}" method="POST">
                          @csrf
                          <input type="hidden" name="_method" value="DELETE">
                          <input type="submit" value="カートから削除する">
                        </form>
                    </div>
                @endforeach
                <div class="text-center p-2">
                  個数:{{ $count }}個<br>
                  <p style="font-size:1.2em; font-weight:bold;">合計金額:{{ number_format($sum) }}円</p>
                  <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg text-center buy-btn">購入する</button>
                  </form>
              @else
                <p class="text-center">カートは空っぽです</p>
              @endif
                  </div>
              <a class="text-center" href="{{ route('item.index') }}">商品一覧へ</a>
          </div>
      </div>
  </div>
</div>
@endsection