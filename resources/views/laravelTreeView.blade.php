    <li>

      {{-- <span class="tf-nc" title="{{ $tree->username }}" onclick="viewUserDetials('{{ $tree->username }}','{{ $tree->name }}','{{ $tree->lastname }}')"> --}}
      <span class="tf-nc" title="{{ $tree->username }}" onclick="updateTree('{{ $tree->id }}')" style="width: 50px; height: 50px; background-image: url('{{ getImage(getFilePath('userProfile').'/'.$tree->image) }}'); display:block;    background-size: contain;background-position: center;background-repeat: no-repeat;">
        {{-- <img  src="{{ getImage(getFilePath('userProfile').'/'.$tree->image) }}" alt=""> --}}
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
