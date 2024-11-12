<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" indent="yes"/>

	<xsl:template match="/">
		<html>
			<head>
				<title>Broneeringute Halduse Leht</title>
				<style>
					body {
					font-family: Arial, sans-serif;
					background-color: lightgrey; 
					margin: 0;
					padding: 20px;
					}
					h1, h2 {
					color: darkslategray; 
					}
					table {
					width: 100%;
					border-collapse: collapse;
					margin: 20px 0;
					background-color: white; 
					}
					th, td {
					padding: 12px;
					text-align: left;
					border: 1px solid lightgray; 
					}
					th {
					background-color: forestgreen; 
					color: white;
					}
					tr:hover {
					background-color: gainsboro; 
					}
					form {
					margin: 20px 0;
					padding: 15px;
					border: 1px solid lightgray; 
					border-radius: 5px;
					background-color: white; 
					}
					label {
					display: block;
					margin: 10px 0 5px;
					}
					input[type="text"], input[type="datetime-local"], select {
					width: calc(100% - 20px);
					padding: 10px;
					margin-bottom: 10px;
					border: 1px solid lightgray; 
					border-radius: 4px;
					}
					input[type="submit"] {
					background-color: forestgreen; 
					color: white; 
					padding: 10px 15px;
					border: none;
					border-radius: 5px;
					cursor: pointer;
					}
					input[type="submit"]:hover {
					background-color: mediumseagreen; 
					}
				</style>
			</head>
			<body>
				<h1>Broneeringud</h1>
				<table>
					<tr>
						<th>Nimi</th>
						<th>Telefon</th>
						<th>Teenuse nimi</th>
						<th>Auto number</th>
						<th>Aeg</th>
					</tr>
					<xsl:for-each select="bookings/booking">
						<tr>
							<td>
								<xsl:value-of select="contact/name"/>
							</td>
							<td>
								<xsl:value-of select="contact/phone"/>
							</td>
							<td>
								<xsl:value-of select="details/service"/>
							</td>
							<td>
								<xsl:value-of select="details/carNumber"/>
							</td>
							<td>
								<xsl:value-of select="datetime"/>
							</td>
						</tr>
					</xsl:for-each>
				</table>

				<h2>Lisa uus broneering</h2>
				<form method="get" action="">
					<label for="newName">Nimi:</label>
					<input type="text" id="newName" name="newName" required=""/>
					<label for="newPhone">Telefon:</label>
					<input type="text" id="newPhone" name="newPhone" required=""/>
					<label for="newDatetime">Aeg:</label>
					<input type="datetime-local" id="newDatetime" name="newDatetime" required=""/>
					<label for="newService">Teenuse nimi:</label>
					<select id="newService" name="newService" required="">
						<xsl:for-each select="bookings/booking/details/service">
							<option value="{.}">
								<xsl:value-of select="."/>
							</option>
						</xsl:for-each>
					</select>
					<label for="newCarNumber">Auto number:</label>
					<input type="text" id="newCarNumber" name="newCarNumber" required=""/>
					<input type="submit" value="Lisa broneering"/>
				</form>

				<h2>Otsi broneeringut auto numbri järgi</h2>
				<form method="get" action="">
					<label for="carNumber">Sisestage auto number:</label>
					<input type="text" id="carNumber" name="carNumber" required=""/>
					<input type="submit" value="Otsi"/>
				</form>

				<h2>Muuda broneeringut</h2>
				<form method="get" action="">
					<label for="updateCarNumber">Sisestage auto number muutmiseks:</label>
					<input type="text" id="updateCarNumber" name="updateCarNumber" required=""/>
					<label for="newTime">Uus aeg:</label>
					<input type="datetime-local" id="newTime" name="newTime" required=""/>
					<label for="newService">Uus teenus:</label>
					<select id="newService" name="newService" required="">
						<xsl:for-each select="bookings/booking/details/service">
							<option value="{.}">
								<xsl:value-of select="."/>
							</option>
						</xsl:for-each>
					</select>
					<input type="submit" value="Muuda broneeringut"/>
				</form>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
