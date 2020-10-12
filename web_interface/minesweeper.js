$(document).ready(function()
{

    minesweeper.init();

    minesweeper.drawGrid(10, 10);

});

var minesweeper = 
{
    init: function()
    {
        minesweeper.initObjects();
    },

    initObjects: function()
    {
        $(window).on( "unload", function() {
            $.ajax({
                type: 'DELETE',
                url: "http://localhost/minesweeper/destroy.php",
                dataType: "json"
            });
        } )

        $(document).on('click', '.cell', function(e) {

            $.ajax(
                {
                    type: 'GET',
                    url: "http://localhost/minesweeper/check_cell.php",
                    dataType: 'json',
                    data: {
                        'row': $(this).attr('row'),
                        'column': $(this).attr("column")
                    },
                    success: function(data) {
                        switch (data.status) {
                            case 1:             //Game over.
                                minesweeper.showCell(data);
                                alert(data.message);
                                $('#startgame').text('New game');
                                $('#startgame').prop('disabled', false);
                                break;
                            case 2:             // Game not started.
                                alert(data.message);
                                break;
                            default:
                                minesweeper.showCell(data);
                                break;
                        }
                    },
                    error: function() {
                        alert('There was some error performing the AJAX call!');
                    }
                },
            );
        })

        $('#startgame').click(function()
        {
            $.ajax({
                type: 'POST',
                url: "http://localhost/minesweeper/create.php",
                dataType: "json"
            });

            $('.cell').detach();
            minesweeper.drawGrid(10,10);

            $('#startgame').text('Game in progress');
            $('#startgame').prop('disabled', true);

        })
    },

    endgame: function(){
        $.ajax({
            type: 'DELETE',
            url: "http://localhost/minesweeper/destroy.php",
            dataType: "json"
        });
    },

    drawGrid: function(rows, columns) {

        cellnumber = 0;
        row = 0;
        column = 0;
        elements = rows * columns;

        for (let element = 0; element < elements; element++) {
            $('#grid').append("<div class='cell' id="+cellnumber+" row="+row+" column="+column+">"); 
            cellnumber++;
            column++;
            if (column >= 10) {
                column = 0;
                row++;
            }
        }

        $("#grid").css("grid-template-columns", "repeat("+columns+", 50px)");
        $("#grid").css("grid-template-rows", "repeat("+rows+", 50px)");

    },

    showCell: function(data) {
        $(".cell[row='" + data.row + "'][column='" + data.column + "']").html("<p>" + data.content + "</p>");
    }
};
