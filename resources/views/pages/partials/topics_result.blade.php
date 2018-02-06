<div class="result">
    <h2 class="title">
        <a href="{{ $topic->link() }}">{{ $topic->title }}</a>
        <small>by</small>
        <a href="{{ route('users.show', [$topic->user_id]) }}" title="{{ $topic->user->name }}">
            <img class="img-thumbnail img-circle"  style="width: 48px;height: 48px" alt="{{ $topic->user->name }}" src="{{ $topic->user->avatar }}"/>
            <small>{{ $topic->user->name }}</small>
        </a>
    </h2>
    <div class="info">
      <span class="url">
          <a href="{{ $topic->link() }}">{{ $topic->link() }}</a>
      </span>
      <span class="date" title="最后更新于">
      {{  $topic->updated_at->toDateTimeString() }}
      </span>
      <i class="fa fa-eye"></i> {{ $topic->view_count }}
      <i class="fa fa-thumbs-o-up"></i> {{ $topic->votes_count }}
      <i class="fa fa-comments-o"></i> {{ $topic->replies_count }}
    </div>
    <div class="desc">
        {{ str_limit($topic->body, 250) }}
    </div>
    <hr>
</div>