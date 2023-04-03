@extends('layouts.app')

@section('title', 'All subscribers')

@section('content')
    @if ($errors->any() || isset($error))
        <div class="notification is-danger">
            <ul>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif

                @if (isset($error))
                    <li>{{ $error }}</li>
                @endif
            </ul>
        </div>
    @endif
    <nav class="level">
        <div class="level-left">
            <form method="get" action="{{ route('subscribers.index') }}" class="level-item">
                <div class="level-item">
                    Items per page:
                </div>
                <div class="level-item select">
                    <select name="per_page">
                        <option value="10" @if ($input['per_page'] == 10) selected @endif>10</option>
                        <option value="25" @if ($input['per_page'] == 25) selected @endif>25</option>
                        <option value="50" @if ($input['per_page'] == 50) selected @endif>50</option>
                    </select>
                </div>
                <div class="level-item">
                    <button class="button" type="submit">Apply</button>
                </div>
            </form>
        </div>
        <div class="level-right">
            <div class="level-item">
                <div class="field has-addons">
                    <p class="control">
                        <input class="input" type="text" placeholder="Email address">
                    </p>
                    <p class="control">
                        <button class="button">
                            Search
                        </button>
                    </p>
                </div>
            </div>
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
        </tr>
        </thead>
        <tbody>
        @if (count($result->data) > 0)
            @foreach($result->data as $subscriber)
                <tr>
                    <td>{{ $subscriber->email }}</td>
                    <td>{{ $subscriber->firstName }} {{ $subscriber->lastName }}</td>
                    <td>{{ $subscriber->country }}</td>
                    <td>{{ $subscriber->subscribeDate }}</td>
                    <td>{{ $subscriber->subscribeTime }}</td>
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
        </tr>
        </tfoot>
    </table>
    <nav class="pagination is-justify-content-flex-end">
        <a href="{{ route('subscribers.index', array_merge($input, ['cursor' => $result->meta->prevCursor])) }}"
           class="pagination-previous" @if (!$result->meta->prevCursor) disabled @endif>Previous</a>
        <a href="{{ route('subscribers.index', array_merge($input, ['cursor' => $result->meta->nextCursor])) }}"
           class="pagination-next" @if (!$result->meta->nextCursor) disabled @endif>Next</a>
    </nav>
@endsection
