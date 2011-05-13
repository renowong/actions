<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<xsl:for-each select="tasks/task">
    <table class="extract">
      <tr>
        <th>
		<span class="underline">Projet :</span> <xsl:value-of select="project"/><br/>
		<span class="underline">Situation :</span> <xsl:value-of select="title"/><br/>
		<span class="underline">Action :</span> <xsl:value-of select="description"/><br/>
		<span class="underline">Proprietaire :</span> <xsl:value-of select="owner"/><br/>
		<span class="underline">Responsable :</span> <xsl:value-of select="incharge"/><br/>
		<span class="underline">Demande faite le :</span> <xsl:value-of select="date"/><br/>
		<span class="underline">Echeance :</span> <xsl:value-of select="deadline"/><br/>
		<span class="underline">Etat d'avancement:</span> <xsl:value-of select="progress"/>%
	</th>
	<td>
		<table class="resultsfollowup">
		<xsl:for-each select="utask">	
			<tr><td>
			<span class="underline">Action :</span> <xsl:value-of select="title"/><br/>
			<span class="underline">Responsable :</span> <xsl:value-of select="incharge"/><br/>
			<span class="underline">Echeance :</span> <xsl:value-of select="deadline"/><br/>
			<span class="underline">Fait le :</span> <xsl:value-of select="enddate"/>
			</td></tr>
		</xsl:for-each>
		</table>
	</td>
      </tr>
    </table>
</xsl:for-each>
</xsl:template>
</xsl:stylesheet>
