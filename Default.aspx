<%@ Page Title="Home Page" Language="C#" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="autoteenindusePr._Default" %>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>XML ja XSLT kuvamine</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <form id="form1" runat="server">
        <h1>XML ja XSLT andmete kuvamine</h1>
        <br />
        <div>
            <asp:Xml runat="server" ID="Xml1" DocumentSource="~/bookings.xml" TransformSource="~/bookings.xslt" />
        </div>
    </form>
</body>
</html>
