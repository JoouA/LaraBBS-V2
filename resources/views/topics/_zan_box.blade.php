<div class="votes-container panel panel-default padding-md">
    <div class="panel-body vote-box text-center">
        @if(Auth::check())
            <div class="btn-group">
                <button href="javascript:void(0);" data-url="{{ route('topics.vote',$topic->id) }}"  vote-zan="{{ Auth::user()->isVote($topic)? 'vote' : 'unVote' }}"
                   vote-user="{{ Auth::id() }}" topic-id="{{ $topic->id }}" id="up-vote" class="btn btn-primary" >
                    {{ Auth::user()->isVote($topic)? '已点赞' : '点赞' }} </button>
            </div>
        @else
            <div class="btn-group">
                <a href="{{ route('login') }}" class="btn btn-primary" >'点赞' </a>
            </div>
        @endif
        <div class="voted-users">
            @if(count($vote_users))
                <div class="user-lists">
                    @foreach($vote_users as $vote_user)
                    <a href="{{ route('users.show',$vote_user->id) }}" data-userId="{{ $vote_user->id }}">
                        <img class="img-thumbnail img-circle avatar-middle" src="{{ $vote_user->avatar }}">
                    </a>
                    @endforeach
                </div>
            @else
                <div class="user-lists">
                </div>
                <div class="vote-hint">
                    成为第一个点赞的人吧 <img title=":bowtie:" alt=":bowtie:" class="emoji" src="https://dn-phphub.qbox.me/assets/images/emoji/bowtie.png" align="absmiddle"></img>
                </div>
            @endif
            <a class="voted-template" href="" data-userId="" style="display:none">
                <img class="img-thumbnail img-circle avatar-middle" src="" style="width:48px;height:48px;">
            </a>
        </div>
    </div>
</div>