package main

import (
	"back/pkg/controllers"
	"back/pkg/db"
	"back/pkg/initializers"
	"github.com/gin-gonic/gin"
)

func init() {
	initializers.LoadEnvVariables()
	db.ConnectToDb()
	db.SyncDatabase()
}

func main() {
	r := gin.Default()

	r.POST("/signup", controllers.Signup1)
	r.POST("/login", controllers.Login1)

	r.Run()
}
