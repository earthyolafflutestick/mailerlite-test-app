@extends('layouts.app')

@section('title', 'All subscribers')

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
    @if (session()->has('success'))
        <div class="notification is-success">
            {{ session('success') }}
        </div>
    @endif
    <nav class="level">
        <div class="level-left">
            <form method="get" action="{{ route('subscribers.index') }}" class="level-item">
                <div class="level-item">
                    Items per page:
                </div>
                <div class="level-item select">
                    <select name="per_page" @if (!isset($result)) disabled @endif>
                        <option value="10" @if ($input['per_page'] == 10) selected @endif>10</option>
                        <option value="25" @if ($input['per_page'] == 25) selected @endif>25</option>
                        <option value="50" @if ($input['per_page'] == 50) selected @endif>50</option>
                    </select>
                </div>
                <div class="level-item">
                    <button class="button" type="submit" @if (!isset($result)) disabled @endif>Apply</button>
                </div>
            </form>
        </div>
        <div class="level-right">
            <form method="get" action="{{ route('subscribers.search') }}" class="level-item">
                <div class="field has-addons">
                    <p class="control">
                        <input name="email" class="input" type="text" placeholder="Email address">
                    </p>
                    <p class="control">
                        <button class="button">
                            Search
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </nav>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Country</th>
            <th>Subscribe date</th>
            <th>Subscribe time</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($result) && count($result->data) > 0)
            @foreach($result->data as $subscriber)
                <tr>
                    <td>
                        <a href="{{ route('subscribers.edit', ['subscriber' => $subscriber->id]) }}">{{ $subscriber->email }}</a>
                    </td>
                    <td>{{ $subscriber->name }}</td>
                    <td>{{ $subscriber->country }}</td>
                    <td>{{ $subscriber->subscribeDate }}</td>
                    <td>{{ $subscriber->subscribeTime }}</td>
                    <td>
                        <form method="POST"
                              action="{{ route('subscribers.destroy', ['subscriber' => $subscriber->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button class="button" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <td colspan="5">{{ __('mailerlite.no_subscribers_found') }}</td>
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Country</th>
            <th>Subscribe date</th>
            <th>Subscribe time</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
    <nav class="pagination is-justify-content-flex-end">
        @if (isset($result))
            <a href="{{ route('subscribers.index', array_merge($input, ['cursor' => $result->meta->prevCursor])) }}"
               class="pagination-previous" @if (!$result->meta->prevCursor) disabled @endif>Previous</a>
            <a href="{{ route('subscribers.index', array_merge($input, ['cursor' => $result->meta->nextCursor])) }}"
               class="pagination-next" @if (!$result->meta->nextCursor) disabled @endif>Next</a>
        @else
            <a href="" class="pagination-previous" disabled>Previous</a>
            <a href="" class="pagination-next" disabled>Next</a>
        @endif
    </nav>
@endsection
