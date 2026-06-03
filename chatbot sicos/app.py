from flask import Flask, render_template, request, jsonify
from database import conectar
import markdown

app = Flask(__name__)

@app.route("/")
def inicio():
    return render_template("index.html")

@app.route("/preguntar", methods=["POST"])
def preguntar():

    pregunta = request.json["mensaje"]

    conexion = conectar()
    cursor = conexion.cursor(dictionary=True)

    cursor.execute("""
    INSERT INTO mensajes(remitente,mensaje)
    VALUES(%s,%s)
    """,("usuario",pregunta))

    conexion.commit()

    cursor.execute(
        """
    SELECT respuesta
    FROM conocimientos
    WHERE pregunta LIKE %s
    LIMIT 1
    """,
        ("%" + pregunta + "%",),
    )

    dato = cursor.fetchone()

    if dato:

        respuesta = dato["respuesta"]

        cursor.execute("""
        INSERT INTO mensajes(remitente, mensaje)
        VALUES(%s,%s)
        """,("ia",respuesta))

        conexion.commit()

        html = markdown.markdown(
        respuesta,
        extensions=["fenced_code"]
        )

        return jsonify({
            "respuesta":html
        })

    return jsonify({
        "respuesta": "<p>No encontré información.</p>"
    })

@app.route("/historial")
def historial():
    
    conexion = conectar()
    cursor = conexion.cursor(dictionary=True)
    
    cursor.execute("""
    SELECT *
    FROM mensajes
    ORDER BY id
    """)
    
    mensajes = cursor.fetchall()

    for mensaje in mensajes:   
        if mensaje["remitente"] == "ia":
        
            mensaje["mensaje"] = markdown.markdown(
            mensaje["mensaje"],
            extensions=["fenced_code"]
            )
        
    return jsonify(mensajes)

if __name__ == "__main__":
    app.run(debug=True)
