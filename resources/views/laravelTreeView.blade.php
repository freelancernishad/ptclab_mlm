    <li>

      {{-- <span class="tf-nc" title="{{ $tree->username }}" onclick="viewUserDetials('{{ $tree->username }}','{{ $tree->name }}','{{ $tree->lastname }}')"> --}}
      <span class="tf-nc" title="{{ $tree->username }}" onclick="updateTree('{{ $tree->id }}')">
        <img  src="https://www.w3schools.com/howto/img_avatar.png" alt="">
        {{-- {{ $tree->username }} --}}
    </span>
      @if(count($tree->children) > 0)
      <ul>
          @foreach($tree->children as $key=>$child)
          @include('laravelTreeView',['tree'=>$child])
        @endforeach
      </ul>
      @endif
    </li>
