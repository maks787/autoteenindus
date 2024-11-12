<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" indent="yes"/>
	<xsl:param name="searchName" select="''"/>

	<xsl:template match="/">
		<html>
			<head>
				<title>Broneeringute Halduse Leht</title>
			</head>
			<body>
				<h1>Broneeringud</h1>

				<form method="get" action="">
					<label for="search">Otsi täisnime või osalise nime järgi:</label>
					<input type="text" id="search" name="searchName" required=""/>
					<input type="submit" value="Otsi"/>
				</form>

				<xsl:if test="string($searchName) != ''">
					<h2>
						Otsing: <xsl:value-of select="$searchName"/>
					</h2>
					<xsl:variable name="matchedBooking" select="bookings/booking[contains(contact/name, $searchName)]"/>

					<xsl:if test="count($matchedBooking) > 0">
						<h3>Leitud broneeringud:</h3>
						<table style="background-color: #ffffe0; border: 1px solid #cccccc;">
							<tr>
								<th>Nimi</th>
								<th>Telefon</th>
								<th>Teenuse nimi</th>
								<th>Auto number</th>
								<th>Aeg</th>
							</tr>
							<xsl:for-each select="$matchedBooking">
								<tr>
									<td>
										<xsl:value-of select="contact/name"/>
									</td>
									<td>
										<xsl:value-of select="contact/@phone"/>
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
					</xsl:if>

					<xsl:if test="count($matchedBooking) = 0">
						<p>
							Ühtegi broneeringut nimega "<xsl:value-of select="$searchName"/>" ei leitud.
						</p>
					</xsl:if>
				</xsl:if>

				<h2>Kõik Broneeringud</h2>
				<table>
					<tr>
						<th>Nimi</th>
						<th>Telefon</th>
						<th>Teenuse nimi</th>
						<th>Auto number</th>
						<th>Aeg</th>
					</tr>
					<xsl:for-each select="bookings/booking">
						<xsl:sort select="datetime" data-type="text" order="ascending"/>
						<tr>
							<td>
								<xsl:value-of select="contact/name"/>
							</td>
							<td>
								<xsl:value-of select="contact/@phone"/>
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
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
