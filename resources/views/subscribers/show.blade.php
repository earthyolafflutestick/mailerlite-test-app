@extends('layouts.app')

@section('title', 'Search subscriber')

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
            <table class="table is-full-width">
                <tbody>
                <tr>
                    <th>Email address</th>
                    <td>{{ $subscriber->email }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $subscriber->firstName }} {{ $subscriber->lastName }}</td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>{{ $subscriber->country }}</td>
                </tr>
                <tr>
                    <th>Subscribe date and time</th>
                    <td>{{ $subscriber->subscribeDate }} at {{ $subscriber->subscribeTime }}</td>
                </tr>
                </tbody>
            </table>
        @endforeach
    @endif
@endsection
