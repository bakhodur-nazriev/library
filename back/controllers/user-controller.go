package controllers

import (
	"ebook-service/helper"
	"github.com/gin-gonic/gin"
	"net/http"
)

func HasPassword() {

}

func VerifyPassword() {

}

func Signup() gin.HandlerFunc {
	return func(c *gin.Context) {
		//var ctx, _ = context.WithTimeout(context.Background(), 100*time.Second)
		//var user models.User
		//
		//if err := c.BindJSON(&user); err != nil {
		//	c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		//	return
		//}
	}
}

func Login() gin.HandlerFunc {
	return func(c *gin.Context) {

	}
}

func GetUsers() gin.HandlerFunc {
	return func(c *gin.Context) {

	}
}

func GetUser() gin.HandlerFunc {
	return func(c *gin.Context) {
		userId := c.Param("user_id")
		if err := helper.MatchUserTypeToUuid(c, userId); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
			return
		}

	}
}
