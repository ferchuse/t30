
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">


<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">


<link href="../../css/sb-admin.css?v=<?php echo date("d-m-i")?>" rel="stylesheet">
<link href="../../css/estilos.css?v=<?php echo date("d-m-y-h-i")?>" rel="stylesheet ">
<link href="../../css/alertify.min.css" rel="stylesheet">

<link rel="manifest" href="../../manifest.webmanifest?v=<?php echo date("d")?>">

<meta name="mobile-web-app-capable" content="yes" />
<style>
/* Interruptores compartidos: conserva los inputs, etiquetas y eventos existentes. */
input.toggle-ios[type="checkbox"]:not(.custom-control-input) {
    -webkit-appearance: none;
    appearance: none;
    width: 44px;
    height: 26px;
    min-width: 44px;
    border: 0;
    border-radius: 999px;
    background: radial-gradient(circle at 13px 13px, #fff 10px, transparent 11px), #aeb4bd;
    vertical-align: middle;
    cursor: pointer;
    transition: background-color .2s ease;
}
input.toggle-ios[type="checkbox"]:not(.custom-control-input):checked {
    background: radial-gradient(circle at 31px 13px, #fff 10px, transparent 11px), #248a3d;
}
.custom-control.toggle-ios {
    padding-left: 3.4rem;
    min-height: 26px;
}
.custom-control.toggle-ios input[type="checkbox"] ~ .custom-control-label {
    min-height: 26px;
    line-height: 26px;
    cursor: pointer;
}
.custom-control.toggle-ios input[type="checkbox"] ~ .custom-control-label::before {
    top: 0;
    left: -3.4rem;
    width: 44px;
    height: 26px;
    border: 0 !important;
    border-radius: 999px;
    background: #aeb4bd !important;
    box-shadow: none;
    transition: background-color .2s ease;
}
.custom-control.toggle-ios input[type="checkbox"] ~ .custom-control-label::after {
    top: 3px;
    left: calc(-3.4rem + 3px);
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #fff !important;
    box-shadow: 0 1px 3px rgba(0,0,0,.22);
    transform: none;
    transition: transform .2s ease;
}
.custom-control.toggle-ios input[type="checkbox"]:checked ~ .custom-control-label::before {
    background: #248a3d !important;
}
.custom-control.toggle-ios input[type="checkbox"]:checked ~ .custom-control-label::after {
    transform: translateX(18px);
}
input.toggle-ios[type="checkbox"]:not(.custom-control-input):focus-visible,
.custom-control.toggle-ios input[type="checkbox"]:focus-visible ~ .custom-control-label::before {
    outline: 3px solid #70b5ff;
    outline-offset: 3px;
}
input.toggle-ios[type="checkbox"]:disabled,
.custom-control.toggle-ios input[type="checkbox"]:disabled ~ .custom-control-label {
    opacity: .5;
    cursor: not-allowed;
}
@media (prefers-reduced-motion: reduce) {
    .toggle-ios .custom-control-label::before, .toggle-ios .custom-control-label::after,
    input.toggle-ios[type="checkbox"] { transition: none !important; }
}
</style>
<link rel="shortcut icon" sizes="192x192" href="../../img/icons/icon-192x192.png?v=<?php echo date("d")?>" />
