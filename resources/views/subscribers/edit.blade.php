@extends('layouts.app')

@section('title', 'Edit subscriber')

@section('content')
    @if ($errors->any() || isset($resultError))
        <div class="notification is-danger">
            <ul>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif

                @if (isset($resultError))
                    <li>{{ $resultError }}</li>
                @endif
            </ul>
        </div>
    @endif
    @if (isset($result) && count($result->data) > 0)
        @foreach($result->data as $subscriber)
            <form method="POST" action="{{ route('subscribers.update', ['subscriber' => $subscriber->id]) }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" type="text" name="name" placeholder="Name"
                               value="{{ $subscriber->name }}"/>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Country</label>
                    <div class="control">
                        <input class="input" type="text" name="country" placeholder="Country"
                               value="{{ $subscriber->country }}"/>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button class="button is-link">Submit</button>
                    </div>
                </div>
            </form>
        @endforeach
    @endif
@endsection
