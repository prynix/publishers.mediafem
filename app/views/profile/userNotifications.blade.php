<a href="/profile#accountMessages" class="btn btn-default">
    @if(count($messages) > 0)
    <i class="fa fa-envelope"></i>
    <span class="badge notification-red">
        {{ count($messages) }}
    </span>
    @else
    <i class="fa fa-envelope-o"></i>
    @endif
</a>