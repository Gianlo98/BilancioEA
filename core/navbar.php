<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="hub.php">Economia Aziendale</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="income_statment.php">Conto Economico</a></li>
                <li ><a href="balance_sheet.php">Stato Patrimoniale</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Altri <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Contro Economico Rielaborato 1</a></li>
                        <li><a href="#">Contro Economico Rielaborato 2</a></li>
                        <li><a href="#">Stato patrimoniale rielaborato</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left">
                <div class="form-group" >
                    <input type="text" class="form-control" placeholder="Ricerca Conto">
                </div>
                <button type="submit" class="btn btn-default">Cerca</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Opzioni <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php">Modifica Conti</a></li>
                        <li><a href="#">Quiz sui conti</a></li>
                        <li><a href="#">Non so che scrivere...</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Esci</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>