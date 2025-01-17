<div>
    <script>
        const :lc:tables = @json($:lc:tables);
    </script>

    <h1 class="text-2xl font-bold mb-4">List :uc:table</h1>
    <div id="theGrid"></div>

    @vite('resources/js/:lc:table/list.js')
</div>