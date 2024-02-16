package helper

import (
	"errors"
	"github.com/gin-gonic/gin"
)

func MatchUserTypeToUuid(c *gin.Context, userId string) error {
	userType := c.GetString("user_type")
	uuid := c.GetString("uuid")

	if userType == "USER" && uuid != userId {
		return errors.New("unauthorized to access this resource")
	}
	err := CheckUserType(c, userType)
	if err != nil {
		return err
	}
	return nil
}

func CheckUserType(c *gin.Context, role string) error {
	userType := c.GetString("user_type")

	if userType != role {
		return errors.New("unauthorized to access this resource")
	}
	return nil
}
